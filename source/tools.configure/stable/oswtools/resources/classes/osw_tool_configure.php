<?php

/**
 *
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package oswFrame - Tools
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 *
 */
class osW_Tool_Configure extends osW_Tool_Object {

	public $data=array();

	function __construct() {
		$this->init();
	}

	function __destruct() {
	}

	function init() {
		$this->data=array();
		$this->data['info']=array();
		$this->data['info']['pages']=1;
		$this->data['info']['page']=0;
		$this->data['values_post']=array();
		$this->data['values_json']=array();
		$this->data['error']=array();
		if ((isset($_POST['page']))&&(intval($_POST['page'])>0)) {
			$this->data['info']['page']=intval($_POST['page']);
		}

		if ((isset($_POST['prev']))&&($_POST['prev']=='prev')) {
			$this->data['info']['page']--;
		}
	}

	function get() {
		return $this->data;
	}

	function getFilesDir($dir, $substrs='') {
		if (is_string($substrs)) {
			$substrs=array($substrs);
		}
		$this->data['files_'.$dir.'_'.serialize($substrs)]=array();
		$directory=abs_path.'resources/php/configure/'.$dir.'/';
		if (is_dir($directory)) {
			$files=glob($directory.'*.php');
			sort($files);
			foreach ($substrs as $substr) {
				foreach ($files as $file) {
					$file=str_replace($directory, '', $file);
					if (substr($file, -4)=='.php') {
						$pos=strstr($file, $substr);
						if (($pos!==false)&&($pos>=0)&&($pos<5)) {
							$this->data['files_'.$dir.'_'.serialize($substrs)][]=array(
								'dir'=>$dir,
								'file'=>$file,
							);
						}
					}
				}
			}
		}
		return $this->data['files_'.$dir.'_'.serialize($substrs)];
	}

	function getFiles() {
		$patch=array();
		$this->data['files']=array();
		foreach (array('top', 'topmiddle', 'middle', 'middlebottom', 'bottom') as $dir) {
			$directory=abs_path.'resources/php/configure/'.$dir.'/';
			if (is_dir($directory)) {
				$files=array_diff(scandir($directory), array('..', '.'));
				foreach ($files as $file) {
					if (substr($file, -4)=='.php') {
						if (!in_array($file, array('__patch.php', '__create.php'))) {
							if (substr($file, 0, 8)=='__patch_') {
								if (!isset($patch[$dir])) {
									$patch[$dir]=true;
									$this->data['info']['pages']++;
									$this->data['files'][]=array(
										'dir'=>$dir,
										'file'=>'__patch.php',
									);
								}
							} elseif (substr($file, 0, 9)=='__create_') {
								if (!isset($patch[$dir])) {
									$patch[$dir]=true;
									$this->data['info']['pages']++;
									$this->data['files'][]=array(
										'dir'=>$dir,
										'file'=>'__create.php',
									);
								}
							} else {
								$this->data['info']['pages']++;
								$this->data['files'][]=array(
									'dir'=>$dir,
									'file'=>$file,
								);
							}
						}
					}
				}
			}
		}
	}

	function loadFile($position) {
		if ($this->data['info']['page']<$this->data['info']['pages']) {
			include(abs_path.'resources/php/configure/'.$this->data['files'][$this->data['info']['page']]['dir'].'/'.$this->data['files'][$this->data['info']['page']]['file']);
		}
	}

	function setDefaultValuesFromJSON() {
		if (file_exists(abs_path.'resources/json/configure/'.$this->data['files'][$this->data['info']['page']]['dir'].'/'.$this->data['files'][$this->data['info']['page']]['file'].'.json')) {
			$values=json_decode(file_get_contents(abs_path.'resources/json/configure/'.$this->data['files'][$this->data['info']['page']]['dir'].'/'.$this->data['files'][$this->data['info']['page']]['file'].'.json'), true);
			foreach ($values as $key => $value) {
				if (isset($this->data['settings']['fields'][$key]['default_type'])) {
					if ($this->data['settings']['fields'][$key]['default_type']!='password') {
						if (isset($this->data['settings']['fields'][$key])) {
							$this->data['settings']['fields'][$key]['default_value']=$value['value'];
						}
					}
				}
			}
		}
	}

	function getValuesFromJSON() {
		foreach ($this->data['files'] as $element) {
			$filename=abs_path.'resources/json/configure/'.$element['dir'].'/'.$element['file'].'.json';
			if (file_exists($filename)) {
				$values=json_decode(file_get_contents($filename), true);
				foreach ($values as $key => $value) {
					$this->data['values_json'][$key]=$value['value'];
				}
			}
		}
	}

	public function validateFields() {
		if(isset($this->data['settings']['fields'])) {
			foreach ($this->data['settings']['fields'] as $config_element => $config_data) {
				if ($config_data['default_type']!='function') {
					if (isset($_POST['conf_'.$config_element])) {
						$this->data['values_post'][$config_element]['value']=$_POST['conf_'.$config_element];
					} else {
						$this->data['values_post'][$config_element]['value']='';
					}
					$this->data['settings']['fields'][$config_element]['default_value']=$this->data['values_post'][$config_element]['value'];
				}

				if(in_array($config_data['default_type'], array('function'))) {
					if (isset($config_data['valid_function'])) {
						$function=abs_path.'resources/php/configure/validation/'.$config_data['valid_function'].'.inc.php';
						if (file_exists($function)) {
							include $function;
						} else {
							$this->data['error'][$config_element]='validation file '.$config_data['valid_function'].'.inc.php is missing';
						}
					}
				} else {
					$this->data['values_post'][$config_element]['type']=$config_data['valid_type'];
					if ((isset($config_data['configure_write']))&&($config_data['configure_write']===true)) {
						$this->data['values_post'][$config_element]['write']=true;
					} else {
						$this->data['values_post'][$config_element]['write']=false;
					}
					$this->data['values_post'][$config_element]['type']=$config_data['valid_type'];

					if (isset($config_data['valid_type'])) {
						$function=abs_path.'resources/php/configure/validation/'.$config_data['valid_type'].'.inc.php';
						if (file_exists($function)) {
							include $function;
						} else {
							$this->data['error'][$config_element]='validation file '.$config_data['valid_type'].'.inc.php is missing';
						}

						if (isset($config_data['valid_function'])) {
							$function=abs_path.'resources/php/configure/validation/'.$config_data['valid_function'].'.inc.php';
							if (file_exists($function)) {
								include $function;
							} else {
								$this->data['error'][$config_element]='validation file '.$config_data['valid_function'].'.inc.php is missing';
							}
						}
					}
				}
			}
		}
	}

	public function writeValuesToJSON() {
		if (isset($this->data['settings']['fields'])) {
			foreach ($this->data['settings']['fields'] as $key => $value) {
				if ($value['default_type']=='password') {
					if (strlen($this->data['values_post'][$key]['value'])>0) {
						$this->data['values_post'][$key]['value']=$this->encryptString($this->data['values_post'][$key]['value']);
					} else {
						$this->data['values_post'][$key]['value']=$this->data['values_json'][$key];
					}
				}
			}
			foreach ($this->data['values_post'] as $key => $value) {
				$this->data['values_json'][$key]=$value['value'];
			}
			if (!is_dir(abs_path.'resources/json/configure/'.$this->data['files'][$this->data['info']['page']]['dir'].'/')) {
				mkdir(abs_path.'resources/json/configure/'.$this->data['files'][$this->data['info']['page']]['dir'].'/', osW_Tool::getInstance()->chmodDir());
			}
			file_put_contents(abs_path.'resources/json/configure/'.$this->data['files'][$this->data['info']['page']]['dir'].'/'.$this->data['files'][$this->data['info']['page']]['file'].'.json', json_encode($this->data['values_post']));
			chmod(abs_path.'resources/json/configure/'.$this->data['files'][$this->data['info']['page']]['dir'].'/'.$this->data['files'][$this->data['info']['page']]['file'].'.json', osW_Tool::getInstance()->chmodFile());
		}
	}

	public function writeConfigure($lastpage=true) {
		if (($this->data['info']['page']==($this->data['info']['pages']-1))||($lastpage!==true)) {
			$configure_output=array();
			$configure_output[]='';
			$configure_output[]='# version '.date('YmdHis').' (created by oswtools) #';
			$configure_output[]='';

			foreach ($this->data['files'] as $element) {
				$write=false;
				$filename=abs_path.'resources/json/configure/'.$element['dir'].'/'.$element['file'].'.json';
				if (file_exists($filename)) {
					$set=false;
					$values=json_decode(file_get_contents($filename), true);
					foreach ($values as $key => $value) {
						if ($value['write']==true) {
							if ($set==false) {
								$configure_output[]='# configure-'.$element['dir'].' '.substr($element['file'], 0, -4).'-block begin #';
								$set=true;
							}
							$write=true;

							switch ($value['type']) {
								case 'integer':
									$configure_output[]='osW_setVar(\''.$key.'\', '.intval($value['value']).');';
									break;
								case 'boolean':
									if (($value['value']=='1')||($value['value']==1)||($value['value']==true)) {
										$configure_output[]='osW_setVar(\''.$key.'\', true);';
									} else {
										$configure_output[]='osW_setVar(\''.$key.'\', false);';
									}
									break;
								case 'string':
								default:
									$configure_output[]='osW_setVar(\''.$key.'\', \''.$value['value'].'\');';
									break;
							}
						}
					}
					if ($write===true) {
						$configure_output[]='# configure-'.$element['dir'].' '.substr($element['file'], 0, -4).'-block end #';
						$configure_output[]='';
					}
				}
			}


			$output='# osWFrame configure block begin #';
			foreach ($configure_output as $line) {
				$output.=$line."\n";
			}
			$output.='# osWFrame configure block end #';

			$ready_status=0;
			$configure_file=root_path.'modules/configure.project.php';


			if (file_exists($configure_file)) {
				$configure_content=file_get_contents($configure_file);

				preg_match('/# osWFrame configure block begin #(.*)# osWFrame configure block end #/Uis', $configure_content, $result);
				if (!isset($result[0])) {
					# createblock
					file_put_contents($configure_file, str_replace('<?php', '<?php'."\n\n".$output, $configure_content));
					@chmod($configure_file, osW_Tool::getInstance()->chmodFile());
					$ready_status=2;
				} else {
					$_content_from_file=preg_replace('/\# version ([0-9]{14}) \(created by oswtools\) \#/', '# blocked #', $result[0]);
					$_content_from_output=preg_replace('/\# version ([0-9]{14}) \(created by oswtools\) \#/', '# blocked #', $output);
					if ($_content_from_file!=$_content_from_output) {
						# update
						$_content_file=preg_replace('/(# osWFrame configure block begin #(.*)# osWFrame configure block end #)/Uis', '# osWFrame_blocked #', $configure_content);
						file_put_contents($configure_file, str_replace('# osWFrame_blocked #', $output, $_content_file));
						@chmod($configure_file, osW_Tool::getInstance()->chmodFile());
						$ready_status=2;
					} else {
						# up2date
					}
				}

			} else {
				# create
				file_put_contents($configure_file, '<?php'."\n\n".$output."\n\n".'?>');
				@chmod($configure_file, osW_Tool::getInstance()->chmodFile());
				$ready_status=1;
			}

			osW_Tool::getInstance()->createConfigureFile();
			osW_Tool::getInstance()->createHtAccessFile();
			osW_Tool::getInstance()->protectDirs();

			return $ready_status;
		}
	}

	public function isLastPage() {
		if ($this->data['info']['page']==($this->data['info']['pages']-1)) {
			return true;
		}
		return false;
	}

	public function incPage() {
		$this->data['info']['page']++;
	}

	public function decPage() {
		$this->data['info']['page']--;
	}

	public function hasError() {
		if (!empty($this->data['error'])) {
			return true;
		}
		return false;
	}

	function make_seed() {
		list($usec, $sec) = explode(' ', microtime());
		return (float) $sec + ((float) $usec * 100000);
	}

	function encryptString($password) {
		mt_srand($this->make_seed());
		$rand = mt_rand();
		$salt=substr(md5($rand), 0, 6);
		$password=hash('sha512', $salt.$password).':'.$salt;
		return $password;
	}

	/**
	 *
	 * @return osW_Tool_Configure
	 */
	public static function getInstance() {
		return parent::getInstance();
	}
}

?>