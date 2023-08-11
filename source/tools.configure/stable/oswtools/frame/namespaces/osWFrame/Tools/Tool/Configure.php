<?php

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license MIT License
 */

namespace osWFrame\Tools\Tool;

use osWFrame\Core as Frame;
use osWFrame\Tools as Tools;

class Configure extends CoreTool {

	use Frame\BaseStaticTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=1;

	/**
	 * Minor-Version der Klasse.
	 */
	private const CLASS_MINOR_VERSION=0;

	/**
	 * Release-Version der Klasse.
	 */
	private const CLASS_RELEASE_VERSION=1;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	/**
	 * @var array
	 */
	protected array $areas=[];

	/**
	 * @var array
	 */
	protected array $files=[];

	/**
	 * @var int
	 */
	protected int $pages=0;

	/**
	 * @var int
	 */
	protected int $page=0;

	/**
	 * @var array
	 */
	protected array $values_post=[];

	/**
	 * @var array
	 */
	protected array $values_json=[];

	/**
	 * @var array
	 */
	protected array $settings=[];

	/**
	 * @var array
	 */
	protected array $fields=[];

	/**
	 * @var object|null
	 */
	protected ?object $osWForm=null;

	/**
	 * Configure constructor.
	 *
	 * @param string $serverlist
	 * @param string $package
	 * @param string $release
	 */
	public function __construct(string $serverlist, string $package, string $release) {
		parent::__construct($serverlist, $package, $release);
		$this->areas=['top', 'topmiddle', 'middle', 'middlebottom', 'bottom'];
		$this->page=1;
		$this->pages=1;
	}

	/**
	 * @return $this
	 */
	public function clearPage():self {
		$this->values_post=[];
		$this->values_json=[];
		$this->settings=[];
		$this->fields=[];

		return $this;
	}

	/**
	 * @return array
	 */
	public function getAreas():array {
		return $this->areas;
	}

	/**
	 * @return array
	 */
	public function getFiles():array {
		if ($this->files==[]) {
			$this->initFiles();
		}

		return $this->files;
	}

	/**
	 * @param int $page
	 * @return $this
	 */
	public function setPage(int $page=1):self {
		if ($page>0) {
			$this->page=$page;
		}

		return $this;
	}

	/**
	 * @return int
	 */
	public function getPage():int {
		return $this->page;
	}

	/**
	 * @return int
	 */
	public function getPages():int {
		return $this->pages;
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public function getSettingAsString(string $key):string {
		if (isset($this->settings[$key])) {
			return $this->settings[$key];
		}

		return '';
	}

	/**
	 * @param object $osW_Form
	 * @return $this
	 */
	public function setForm(object &$osW_Form):self {
		$this->osW_Form=$osW_Form;

		return $this;
	}

	/**
	 * @return object
	 */
	public function getForm():object {
		return $this->osW_Form;
	}

	/**
	 * @return $this
	 */
	public function initFiles():self {
		$patch=[];
		$this->files=[];
		foreach ($this->getAreas() as $dir) {
			$directory=Frame\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'configure'.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR;
			if (Frame\Filesystem::isDir($directory)) {
				$files=array_diff(scandir($directory), ['..', '.']);
				foreach ($files as $file) {
					if (substr($file, -4)=='.php') {
						if (!in_array($file, ['__patch.php', '__create.php'])) {
							if (substr($file, 0, 8)=='__patch_') {
								if (!isset($patch[$dir])) {
									$patch[$dir]=true;
									$this->pages++;
									$this->files[]=['dir'=>$dir, 'file'=>'__patch.php',];
								}
							} elseif (substr($file, 0, 9)=='__create_') {
								if (!isset($patch[$dir])) {
									$patch[$dir]=true;
									$this->pages++;
									$this->files[]=['dir'=>$dir, 'file'=>'__create.php',];
								}
							} else {
								$this->pages++;
								$this->files[]=['dir'=>$dir, 'file'=>$file,];
							}
						}
					}
				}
			}
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	public function runFile():self {
		return $this->loadFile('run');
	}

	/**
	 * @return $this
	 */
	public function validateFile():self {
		return $this->loadFile('validate');
	}

	/**
	 * @param string $position
	 * @return $this
	 */
	public function loadFile(string $position):self {
		if ($this->page<$this->pages) {
			$page=$this->page-1;
			$file=Frame\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'configure'.DIRECTORY_SEPARATOR.$this->files[$page]['dir'].DIRECTORY_SEPARATOR.$this->files[$page]['file'];
			include $file;
			$this->setDefaultValuesFromJSON();
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	public function setDefaultValuesFromJSON():self {
		$page=$this->page-1;
		$filename=Frame\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'configure'.DIRECTORY_SEPARATOR.$this->files[$page]['dir'].DIRECTORY_SEPARATOR.$this->files[$page]['file'].'.json';
		if (Frame\Filesystem::existsFile($filename)) {
			$values=json_decode(file_get_contents($filename), true);
			foreach ($values as $key=>$value) {
				if (isset($this->fields[$key]['default_type'])) {
					if (($this->fields[$key]['default_type']!='password')&&($this->fields[$key]['default_type']!='hidden')) {
						if (isset($this->fields[$key])) {
							$this->fields[$key]['default_value']=$value['value'];
						}
					}
				}
			}
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	public function loadValuesFromJSON():self {
		if ($this->files==[]) {
			$this->initFiles();
		}

		foreach ($this->files as $element) {
			$filename=Frame\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'configure'.DIRECTORY_SEPARATOR.$element['dir'].DIRECTORY_SEPARATOR.$element['file'].'.json';
			if (Frame\Filesystem::existsFile($filename)) {
				$values=json_decode(file_get_contents($filename), true);
				foreach ($values as $key=>$value) {
					$this->values_json[$key]=$value['value'];
				}
			}
		}

		return $this;
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public function getJSONStringValue(string $key):string {
		if (isset($this->values_json[$key])) {
			return $this->values_json[$key];
		}

		return '';
	}

	/**
	 * @param string $key
	 * @return int
	 */
	public function getJSONIntValue(string $key):int {
		if (isset($this->values_json[$key])) {
			return intval($this->values_json[$key]);
		}

		return 0;
	}

	/**
	 * @return $this
	 */
	public function writeValuesToJSON():self {
		if ($this->fields!=[]) {
			#print_a($this->fields);
			foreach ($this->fields as $key=>$value) {
				if ($value['default_type']=='password') {
					if (strlen($this->values_post[$key]['value'])>0) {
						$this->values_post[$key]['value']=Frame\StringFunctions::encryptString($this->values_post[$key]['value']);
					} else {
						$this->values_post[$key]['value']=$this->values_json[$key];
					}
				}
			}
			#print_a($this->values_post);
			foreach ($this->values_post as $key=>$value) {
				$this->values_json[$key]=$value['value'];
			}

			#print_a($this->values_json);
			$page=$this->page-1;
			$dir=Frame\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'configure'.DIRECTORY_SEPARATOR.$this->files[$page]['dir'].DIRECTORY_SEPARATOR;
			if (Frame\Filesystem::isDir($dir)!==true) {
				Frame\Filesystem::makeDir($dir, Tools\Configure::getFrameConfigInt('settings_chmod_dir'));
			}
			$file=$dir.$this->files[$page]['file'].'.json';
			file_put_contents($file, json_encode($this->values_post));
			Frame\Filesystem::changeFilemode($file, Tools\Configure::getFrameConfigInt('settings_chmod_file'));
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function getFields():array {
		return $this->fields;
	}

	/**
	 * @return $this
	 */
	public function validateFields():self {
		if (isset($this->fields)) {
			foreach ($this->fields as $config_element=>$config_data) {
				if ($config_data['default_type']!='function') {
					if (isset($_POST['conf_'.$config_element])) {
						$this->values_post[$config_element]['value']=$_POST['conf_'.$config_element];
					} else {
						$this->values_post[$config_element]['value']='';
					}
					if ($config_data['default_type']!='hidden') {
						$this->fields[$config_element]['default_value']=$this->values_post[$config_element]['value'];
					}
				}

				if (in_array($config_data['default_type'], ['function'])) {
					if (isset($config_data['valid_function'])) {
						$function=Frame\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'configure'.DIRECTORY_SEPARATOR.'validation'.DIRECTORY_SEPARATOR.$config_data['valid_function'].'.inc.php';
						if (Frame\Filesystem::existsFile($function)) {
							include $function;
						} else {
							$this->getForm()->addErrorMessage('conf_'.$config_element, 'validation file '.$config_data['valid_function'].'.inc.php is missing');
						}
					}
				} else {
					$this->values_post[$config_element]['type']=$config_data['valid_type'];
					if ((isset($config_data['configure_write']))&&($config_data['configure_write']===true)) {
						$this->values_post[$config_element]['write']=true;
					} else {
						$this->values_post[$config_element]['write']=false;
					}
					$this->values_post[$config_element]['type']=$config_data['valid_type'];

					if (isset($config_data['valid_type'])) {
						$function=Frame\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'configure'.DIRECTORY_SEPARATOR.'validation'.DIRECTORY_SEPARATOR.$config_data['valid_type'].'.inc.php';
						if (Frame\Filesystem::existsFile($function)) {
							include $function;
						} else {
							$this->getForm()->addErrorMessage('conf_'.$config_element, 'validation file '.$config_data['valid_type'].'.inc.php is missing');
						}

						if (isset($config_data['valid_function'])) {
							$function=Frame\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'configure'.DIRECTORY_SEPARATOR.'validation'.DIRECTORY_SEPARATOR.$config_data['valid_function'].'.inc.php';
							if (Frame\Filesystem::existsFile($function)) {
								include $function;
							} else {
								$this->getForm()->addErrorMessage('conf_'.$config_element, 'validation file '.$config_data['valid_function'].'.inc.php is missing');
							}
						}
					}
				}
			}
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function getValuesFromJSON():array {
		if ($this->values_json==[]) {
			$this->loadValuesFromJSON();
		}

		return $this->values_json;
	}

	/**
	 * @return bool
	 */
	public function isFirstPage():bool {
		if ($this->page==1) {
			return true;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function isLastPage():bool {
		if ($this->page==$this->pages) {
			return true;
		}

		return false;
	}

	/**
	 * @return $this
	 */
	public function incPage():self {
		$this->page++;

		return $this;
	}

	/**
	 * @return $this
	 */
	public function decPage():self {
		$this->page--;

		return $this;
	}

	/**
	 * @return $this
	 */
	public function writeConfigure():self {
		$configure_output=[];
		$configure_output[]='';
		$configure_output[]='# version '.date('YmdHis').' (created by osWTools) #';
		$configure_output[]='';

		foreach ($this->files as $element) {
			$write=false;
			$filename=Frame\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'configure'.DIRECTORY_SEPARATOR.$element['dir'].DIRECTORY_SEPARATOR.$element['file'].'.json';
			if (Frame\Filesystem::existsFile($filename)) {
				$set=false;
				$values=json_decode(file_get_contents($filename), true);
				foreach ($values as $key=>$value) {
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
							case 'vendor':
								if ($values[$key]['value']==0) {
									$v=explode(';', $values[$key.'s']['value']);
									$configure_output[]='osW_setVar(\''.$key.'\', \''.$v[0].'\');';
								} else {
									$configure_output[]='osW_setVar(\''.$key.'\', \''.$value['value'].'\');';
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
		$configure_file=Frame\Settings::getStringVar('settings_framepath').'modules'.DIRECTORY_SEPARATOR.'configure.project.php';
		if (Frame\Filesystem::existsFile($configure_file)) {
			$configure_content=file_get_contents($configure_file);
			preg_match('/# osWFrame configure block begin #(.*)# osWFrame configure block end #/Uis', $configure_content, $result);
			if (!isset($result[0])) {
				/* createblock */
				file_put_contents($configure_file, str_replace('<?php', '<?php'."\n\n".$output, $configure_content));
				Frame\MessageStack::addMessage('configure', 'success', ['msg'=>'file "modules/configure.project.php" updated successfully.']);
			} else {
				$_content_from_file=preg_replace('/\# version ([0-9]{14}) \(created by osWTools\) \#/', '# blocked #', $result[0]);
				$_content_from_output=preg_replace('/\# version ([0-9]{14}) \(created by osWTools\) \#/', '# blocked #', $output);
				if ($_content_from_file!=$_content_from_output) {
					/* update */
					$_content_file=preg_replace('/(# osWFrame configure block begin #(.*)# osWFrame configure block end #)/Uis', '# osWFrame_blocked #', $configure_content);
					file_put_contents($configure_file, str_replace('# osWFrame_blocked #', $output, $_content_file));
					Frame\MessageStack::addMessage('configure', 'success', ['msg'=>'file "modules/configure.project.php" updated successfully.']);
				} else {
					/* up2date */
					Frame\MessageStack::addMessage('configure', 'success', ['msg'=>'file "modules/configure.project.php" is up to date.']);
				}
			}
		} else {
			/* create */
			file_put_contents($configure_file, '<?php'."\n\n".$output."\n\n".'?>');
			Frame\MessageStack::addMessage('configure', 'success', ['msg'=>'file "modules/configure.project.php" created successfully.']);
		}

		Frame\Filesystem::changeFilemode($configure_file, Tools\Configure::getFrameConfigInt('settings_chmod_file'));

		$osW_Manager=new Tools\Manager();
		$osW_Manager->createConfigureFile();
		$osW_Manager->createHtAccessFile();
		$osW_Manager->protectDirs();

		return $this;
	}

}

?>