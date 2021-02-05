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
class osW_Tool extends osW_Tool_Object {

	public $data=[];

	private $doaction='';

	private $action='';

	private $vars=[];

	function __construct() {
		$this->setDoAction($this->_catch('doaction', '', 'pg'));
		$this->setAction($this->_catch('action', 'start', 'pg'));
	}

	function __destruct() {
	}

	public function setDoAction($action) {
		$this->doaction=$action;
	}

	public function getDoAction() {
		return $this->doaction;
	}

	public function setAction($action) {
		$this->action=$action;
	}

	public function getAction() {
		return $this->action;
	}

	public function validateActions($actions, $action_default='start') {
		if (in_array($this->getAction(), $actions)) {
			return $this->getAction();
		}

		return $action_default;
	}

	public function prepaireNavigation($navigation) {
		foreach ($navigation as $key=>$values) {
			$navigation[$key]['actions']=[];
			if (isset($values['links'])) {
				unset($values['actions']);
				foreach ($values['links'] as $value) {
					if (isset($value['action'])) {
						$navigation[$key]['actions'][]=$value['action'];
					}
				}
			}
			if (isset($values['action'])) {
				$navigation[$key]['actions'][]=$values['action'];
			}
		}

		return $navigation;
	}

	public function initTool() {
		$name='tool';
		$file=abs_path.'resources/json/package/'.package.'-'.release.'.json';
		$this->data[$name]=[];
		if (file_exists($file)) {
			$info=json_decode(file_get_contents($file), true);
			$this->data[$name]['name']='';
			$this->data[$name]['author']='';
			$this->data[$name]['copyright']='';
			$this->data[$name]['link']='';
			$this->data[$name]['license']='';
			$this->data[$name]['package']='';
			$this->data[$name]['release']='';
			$this->data[$name]['version']='';
			foreach ($info['info'] as $key=>$value) {
				$this->data[$name][$key]=$value;
			}
		}
		$this->data[$name]['server']='';
		$this->data[$name]['updtserver']='';
		$this->data[$name]['connected']=false;
		$this->data[$name]['updterror']=false;
		$this->data[$name]['abspath']=abs_path;
	}

	public function getToolValue($key) {
		$name='tool';
		if (isset($this->data[$name][$key])) {
			return $this->data[$name][$key];
		}

		return '';
	}

	public function getUpdateVersion($serverlist='oswframe2k20') {
		$file=abs_path.'resources/json/package/'.package.'-'.release.'.json';
		if (file_exists($file)) {
			$info=json_decode(file_get_contents($file), true);
			$server_data=osW_Tool_Server::getInstance()->getConnectedServer($serverlist);
			if ((isset($server_data['connected']))&&($server_data['connected']===true)) {
				$package_version=osW_Tool_Server::getInstance()->getUrlData($server_data['server_url'].'?action=get_version&package='.package.'&release='.release.'&version='.$info['info']['version']);

				return $package_version;
			}
		}

		return '0.0';
	}

	public function checkUpdate($serverlist='oswframe2k20') {
		$file=abs_path.'resources/json/package/'.package.'-'.release.'.json';
		if (file_exists($file)) {
			$info=json_decode(file_get_contents($file), true);
			$server_data=osW_Tool_Server::getInstance()->getConnectedServer($serverlist);
			if ((isset($server_data['connected']))&&($server_data['connected']===true)) {
				$package_version=osW_Tool_Server::getInstance()->getUrlData($server_data['server_url'].'?action=get_version&package='.package.'&release='.release.'&version='.$info['info']['version']);
				if (osW_Tool::getInstance()->checkVersion($this->getToolValue('version'), $package_version)) {
					return true;
				}
			}
		}

		return false;
	}

	public function update($serverlist='oswframe2k20') {
		$file=abs_path.'resources/json/package/'.package.'-'.release.'.json';
		if (file_exists($file)) {
			$info=json_decode(file_get_contents($file), true);
			$server_data=osW_Tool_Server::getInstance()->getConnectedServer($serverlist);
			if ((isset($server_data['connected']))&&($server_data['connected']===true)) {
				$package_version=osW_Tool_Server::getInstance()->getUrlData($server_data['server_url'].'?action=get_version&package='.package.'&release='.release.'&version='.$info['info']['version']);
				if (osW_Tool::getInstance()->checkVersion($this->getToolValue('version'), $package_version)) {
					if ($this->installPackage(package, release, $serverlist)===true) {
						$this->_direct('../'.package.'.'.release.'/index.php?session='.osW_Tool_Session::getInstance()->getId());
					}
				}
			}
		}

		return false;
	}

	public function installPackageForce($package, $release, $serverlist='oswframe2k20', $file='', $dir='') {
		$return=true;
		$server_data=osW_Tool_Server::getInstance()->getConnectedServer($serverlist);
		if ((isset($server_data['connected']))&&($server_data['connected']===true)) {
			$package_checksum=osW_Tool_Server::getInstance()->getUrlData($server_data['server_url'].'?action=get_checksum&package='.$package.'&release='.$release.'&version=0');
			$package_data=osW_Tool_Server::getInstance()->getUrlData($server_data['server_url'].'?action=get_content&package='.$package.'&release='.$release.'&version=0');
			if ($package_checksum==sha1($package_data)) {
				if ($file=='') {
					$file=abs_path.'resources/caches/'.$package.'-'.$release.'.zip';
				}
				if ($dir=='') {
					if (defined('root_path')) {
						$dir=root_path;
					} else {
						$dir=abs_path;
					}
				}
				file_put_contents($file, $package_data);
				osW_Tool_Zip::getInstance()->unpackDir($file, $dir);
				$this->delFile($file);

				$json_file=abs_path.'resources/json/package/'.$package.'-'.$release.'.json';
				if (file_exists($json_file)) {
					$json_data=json_decode(file_get_contents($json_file), true);
					if (isset($json_data['required'])) {
						foreach ($json_data['required'] as $package=>$package_data) {
							$status=$this->installPackage($package_data['package'], $package_data['release'], $package_data['serverlist'], $file, $dir);
							if ($status===false) {
								$return=false;
							}
						}
					}
				}
			} else {
				$return=false;
			}
		} else {
			$return=false;
		}

		return $return;
	}

	public function getPackagesProceed() {
		return $this->data['package_proceed'];
	}

	public function installPackage($package, $release, $serverlist='oswframe2k20', $file='', $dir='') {
		$server_data=osW_Tool_Server::getInstance()->getConnectedServer($serverlist);
		if ((isset($server_data['connected']))&&($server_data['connected']===true)) {
			$return=true;
			$package_list=osW_Tool_Server::getInstance()->getPackageList($serverlist);
			$package_list=osW_Tool_Server::getInstance()->checkPackageList($package_list);
			$p=$package.'-'.$release;
			if ((isset($package_list[$p]))&&(($package_list[$p]['options']['install']===true)||($package_list[$p]['options']['update']===true))&&($package_list[$p]['options']['blocked']!==true)) {
				if ($this->installPackageForce($package, $release, $serverlist, $file, $dir)!==true) {
					$return=false;
				}
				$this->createConfigureFile();
				$this->createHtAccessFile();
			} else {
				$return=false;
			}
			$this->data['package_proceed'][]=['package'=>$package, 'release'=>$release, 'serverlist'=>$serverlist];
		} else {
			$return=false;
		}

		return $return;
	}

	public function removePackage($package, $release, $file='', $dir='') {
		if ($file=='') {
			$file=abs_path.'resources/caches/'.$package.'-'.$release.'.tar.gz';
		}
		if ($dir=='') {
			if (defined('root_path')) {
				$dir=root_path;
			} else {
				$dir=abs_path;
			}
		}
		$file_filelist=abs_path.'resources/json/filelist/'.$package.'-'.$release.'.json';
		if (file_exists($file_filelist)) {
			$dir=substr($dir, 0, -1);
			$filelist=json_decode(file_get_contents($file_filelist), true);
			krsort($filelist);
			if (count($filelist)>0) {
				foreach ($filelist as $entry=>$foo) {
					if (is_file($dir.$entry)) {
						$this->delFile($dir.$entry);
					}
					if (is_dir($dir.$entry)) {
						@rmdir($dir.$entry);
					}
				}
			}
		}
		if (file_exists($file)) {
			$this->delFile($file);
		}
		$this->createConfigureFile();
		$this->createHtAccessFile();
		if (isset($filelist)&&(count($filelist)>0)) {
			foreach ($filelist as $entry=>$foo) {
				if (is_file($dir.$entry)) {
					$this->delFile($dir.$entry);
				}
				if (is_dir($dir.$entry)) {
					$this->delFile($dir.$entry);
				}
			}
		}

		return true;
	}

	// v1=current
	// v2=update
	public function checkVersion($v1, $v2) {
		$v1=explode('.', $v1);
		$v2=explode('.', $v2);

		if ((count($v1)!=2)||(count($v2)!=2)) {
			return true;
		}

		if ((intval($v1[0]))<(intval($v2[0]))) {
			return true;
		}
		if ((intval($v1[1]))<(intval($v2[1]))) {
			return true;
		}

		return false;
	}

	public function createConfigureFile() {
		$dir=abs_path.'resources/json/configure/';
		$configure=[];

		if (is_dir($dir)) {
			foreach (@scandir($dir) as $file) {
				if (substr($file, -5)=='.json') {
					$configure[$file]['configure']=json_decode(file_get_contents($dir.$file), true);
				}
			}
		}

		$configure_file_php=root_path.'frame/configure.php';
		if (!empty($configure)) {
			$configure_output=[];
			$configure_output[]='';
			$configure_output[]='';
			$configure_output[]='/**';
			$configure_output[]=' * @author Juergen Schwind';
			$configure_output[]=' * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)';
			$configure_output[]=' * @package osWFrame';
			$configure_output[]=' * @version '.date('YmdHis').' (created by osWTools)';
			$configure_output[]=' * @link https://oswframe.com';
			$configure_output[]=' * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3';
			$configure_output[]=' *';
			$configure_output[]=' */';
			$configure_output[]='';

			$count_header=count($configure_output);

			$this->vars=[];
			$this->vars['error_reporting_E_ALL']=E_ALL;
			$this->vars['error_reporting_E_ERROR']=E_ERROR;
			$this->vars['error_reporting_E_WARNING']=E_WARNING;
			$this->vars['error_reporting_E_PARSE']=E_PARSE;
			$this->vars['error_reporting_E_NOTICE']=E_NOTICE;
			foreach (['top', 'topmiddle', 'middle', 'middlebottom', 'bottom'] as $part) {
				foreach ($configure as $configure_file=>$configure_data) {
					if (isset($configure_data['configure']['configure'][$part])) {
						$configure_output[]='/* config-'.$part.' '.substr($configure_file, 0, -5).' */';
						foreach ($configure_data['configure']['configure'][$part] as $key=>$value) {
							$this->vars[$key]=$value;
							$premod=0;
							if (is_array($value)) {
								$configure_output[]='osW_setVar(\''.$key.'\', '.json_encode($value).');';
							} else {
								if (substr($value, 0, 3)=='###') {
									$value=str_replace('$vars', '$this->vars', $value);
									$value=eval('return '.substr($value, 3).'?>');
								}
								if ($key=='settings_chmod_file'||$key=='settings_chmod_dir') {
									$value=$premod.intval($value);
								}
								if (is_bool($value)) {
									if ($value===true) {
										$configure_output[]='osW_setVar(\''.$key.'\', true);';
									} else {
										$configure_output[]='osW_setVar(\''.$key.'\', false);';
									}
								} elseif (is_numeric($value)) {
									$configure_output[]='osW_setVar(\''.$key.'\', '.str_replace(',', '.', $value).');';
								} else {
									$configure_output[]='osW_setVar(\''.$key.'\', \''.$value.'\');';
								}
							}
						}
						$configure_output[]='';
					}
				}
			}

			if ($count_header<count($configure_output)) {
				$output='<?php';
				foreach ($configure_output as $line) {
					$output.=$line."\n";
				}
				$output.='?>';

				if (file_exists($configure_file_php)) {
					$configure_content=file_get_contents($configure_file_php);

					if (sha1(preg_replace('/\* \@version ([0-9]{14}) \(created by oswtools\)/', '* blocked', $output))!=sha1(preg_replace('/\* \@version ([0-9]{14}) \(created by oswtools\)/', '* blocked', $configure_content))) {
						file_put_contents($configure_file_php, $output);
						@chmod($configure_file_php, $this->chmodDir());

						return true;
					}
				} else {
					file_put_contents($configure_file_php, $output);
					@chmod($configure_file_php, $this->chmodDir());

					return true;
				}
			} elseif (file_exists($configure_file_php)) {
				$this->delFile($configure_file_php);

				return true;
			}
		} elseif (file_exists($configure_file_php)) {
			$this->delFile($configure_file_php);

			return true;
		}

		return false;
	}

	public function osW_setVar($key, $value) {
		$this->data['configure'][$key]=$value;
	}

	public function createHtAccessFile() {
		$configure_files=[root_path.'frame/configure.php', root_path.'modules/configure.project.php', root_path.'modules/configure.project-dev.php'];

		$this->vars=[];
		foreach ($configure_files as $configure_file) {
			if (file_exists($configure_file)) {
				$content=file_get_contents($configure_file);
				$content=str_replace('settings_abspath', 'abs_path', $content);
				$content=str_replace('osW_setVar(', '$this->osW_setVar(', $content);
				eval(substr($content, 5));
			}
		}

		if (isset($this->vars['project_path'])&&($this->vars['project_path']!='')) {
			$this->vars['project_url_path']='/'.$this->vars['project_path'].'/';
		} else {
			$this->vars['project_url_path']='/';
		}

		$dir=abs_path.'resources/json/configure/';
		$configure=[];

		if (is_dir($dir)) {
			foreach (@scandir($dir) as $file) {
				if (substr($file, -5)=='.json') {
					$configure[$file]['configure']=json_decode(file_get_contents($dir.$file), true);
				}
			}
		}

		$htaccess_file=root_path.'.htaccess';
		if (!empty($configure)) {
			$configure_output=[];
			$configure_output[]='';
			$configure_output[]='# version '.date('YmdHis').' (created by oswtools) #';
			$configure_output[]='';
			$configure_output[]='RewriteEngine on';
			$configure_output[]='';

			$count_header=count($configure_output);

			foreach (['top', 'topmiddle', 'middle', 'middlebottom', 'bottom'] as $part) {
				foreach ($configure as $configure_file=>$configure_data) {
					if (isset($configure_data['configure']['htaccess'][$part])) {
						$configure_output[]='# htaccess-'.$part.' '.substr($configure_file, 0, -5).'-block begin #';
						foreach ($configure_data['configure']['htaccess'][$part] as $line) {
							foreach ($this->vars as $key=>$value) {
								if (!is_array($value)) {
									$line=str_replace('###$vars[\''.$key.'\']', $value, $line);
								}
							}
							foreach ($this->data['configure'] as $key=>$value) {
								if (!is_array($value)) {
									$line=str_replace('###$vars[\''.$key.'\']', $value, $line);
								}
							}
							$configure_output[]=$line;
						}
						$configure_output[]='# htaccess-'.$part.' '.substr($configure_file, 0, -5).'-block end #';
						$configure_output[]='';
					}
				}
			}

			if ($count_header<count($configure_output)) {
				$output='# osWFrame .htaccess block begin #';
				foreach ($configure_output as $line) {
					$output.=$line."\n";
				}
				$output.='# osWFrame .htaccess block end #';

				if (file_exists($htaccess_file)) {
					$htaccess_content=file_get_contents($htaccess_file);
					if (sha1(preg_replace('/\# version ([0-9]{14}) \(created by oswtools\) \#/', '# blocked #', $output))!=sha1(preg_replace('/\# version ([0-9]{14}) \(created by oswtools\) \#/', '# blocked #', $htaccess_content))) {
						if (preg_match('/# osWFrame .htaccess block begin #(.*)# osWFrame .htaccess block end #/Uis', $htaccess_content, $result)==1) {
							# osWFrame .htaccess block begin #
							if (sha1(preg_replace('/\# version ([0-9]{14}) \(created by oswtools\) \#/', '# osWFrame_blocked #', $output))!=sha1(preg_replace('/\# version ([0-9]{14}) \(created by oswtools\) \#/', '# osWFrame_blocked #', $result[1]))) {
								file_put_contents($htaccess_file, str_replace('# osWFrame_blocked #', $output, preg_replace('/(# osWFrame .htaccess block begin #)(.*)(# osWFrame .htaccess block end #)/Uis', '# osWFrame_blocked #', $htaccess_content)));
								@chmod($htaccess_file, $this->chmodDir());

								return true;
							}
						} else {
							file_put_contents($htaccess_file, $output."\n\n".$htaccess_content);
							@chmod($htaccess_file, $this->chmodDir());

							return true;
						}
					}
				} else {
					file_put_contents($htaccess_file, $output);
					@chmod($htaccess_file, $this->chmodDir());

					return true;
				}
			} elseif (file_exists($htaccess_file)) {
				$this->delFile($htaccess_file);
			}
		} elseif (file_exists($htaccess_file)) {
			$this->delFile($htaccess_file);

			return true;
		}

		return false;
	}

	public function protectDirs() {
		$configure_files=[root_path.'frame/configure.php', root_path.'modules/configure.project.php', root_path.'modules/configure.project-dev.php'];

		$this->vars=[];
		foreach ($configure_files as $configure_file) {
			if (file_exists($configure_file)) {
				$content=file_get_contents($configure_file);
				$content=str_replace('settings_abspath', 'abs_path', $content);
				$content=str_replace('osW_setVar(', '$this->osW_setVar(', $content);
				eval(substr($content, 5));
			}
		}

		if (isset($this->vars['project_path'])&&($this->vars['project_path']!='')) {
			$this->vars['project_url_path']='/'.$this->vars['project_path'].'/';
		} else {
			$this->vars['project_url_path']='/';
		}

		$dir=abs_path.'resources/json/configure/';
		$configure=[];

		if (is_dir($dir)) {
			foreach (@scandir($dir) as $file) {
				if (substr($file, -5)=='.json') {
					$configure[$file]['configure']=json_decode(file_get_contents($dir.$file), true);
				}
			}
		}

		$protect_dirs=[];
		$protect_dirs['var']=[];
		$protect_dirs['path']=[];

		if (!empty($configure)) {
			foreach (['var', 'path'] as $part) {
				foreach ($configure as $configure_file=>$configure_data) {
					if (isset($configure_data['configure']['protectdir'][$part])) {
						foreach ($configure_data['configure']['protectdir'][$part] as $line) {
							$protect_dirs[$part][]=$line;
						}
					}
				}
			}

			if ($protect_dirs['var']!=[]) {
				foreach ($protect_dirs['var'] as $value) {
					$protect_dirs['path'][]=$this->getFrameConfig($value);
				}
			}

			if ($protect_dirs['path']!=[]) {
				foreach ($protect_dirs['path'] as $_dir) {
					if (substr($_dir, -1)=='/') {
						$_dir=substr($_dir, 0, -1);
					}
					if (strpos($_dir, '/')>0) {
						$_dirs=explode('/', $_dir);
						$cdir=root_path;
						foreach ($_dirs as $udir) {
							$cdir.=$udir.'/';
							if (is_dir($cdir)!==true) {
								mkdir($cdir);
								@chmod($cdir, $this->chmodDir());
							}
						}
					} else {
						$cdir=root_path.$_dir.'/';
						if (is_dir($cdir)!==true) {
							mkdir($cdir);
							@chmod($cdir, $this->chmodDir());
						}
					}
					$file=$cdir.'.htaccess';
					if (file_exists($file)!==true) {
						file_put_contents($file, "order deny,allow\ndeny from all");
						@chmod($file, $this->chmodFile());
					}
				}
			}
		}

		return false;
	}

	public function loadFrameConfig() {
		if (!isset($this->data['configure'])) {
			$configure_files=[root_path.'frame/configure.php', root_path.'modules/configure.project.php', root_path.'modules/configure.project-dev.php'];

			$this->data['configure']=[];
			foreach ($configure_files as $configure_file) {
				if (file_exists($configure_file)) {
					$content=file_get_contents($configure_file);
					$content=str_replace('settings_abspath', 'abs_path', $content);
					$content=str_replace('osW_setVar(', '$this->osW_setVar(', $content);
					eval(substr($content, 5));
				}
			}
		}

		return $this->data['configure'];
	}

	public function getFrameConfig($var, $type='string') {
		if (!isset($this->data['configure'])) {
			$this->loadFrameConfig();
		}
		if (isset($this->data['configure'][$var])) {
			return $this->data['configure'][$var];
		}

		return '';
	}

	public function chmodFile() {
		$chmod=$this->getFrameConfig('settings_chmod_file');
		if ($chmod=='') {
			$chmod=0666;
		}

		return $chmod;
	}

	public function chmodDir() {
		$chmod=$this->getFrameConfig('settings_chmod_dir');
		if ($chmod=='') {
			$chmod=0777;
		}

		return $chmod;
	}

	public function delFile($file) {
		if (@unlink($file)===true) {
			return true;
		} else {
			$lines=[];
			$deleteError=0;
			if (is_file($file)) {
				// ToDo: Cache doesn't work
				exec('DEL /F/Q "'.$file.'"');
			}
		}
	}

	public function delTree($dir) {
		$files=array_diff(scandir($dir), ['.', '..']);
		foreach ($files as $file) {
			if (is_dir($dir.'/'.$file)) {
				$this->delTree($dir.'/'.$file);
			} else {
				$this->delFile($dir.'/'.$file);
			}
		}

		return rmdir($dir);
	}

	public function _catch($name, $value, $types, $array='') {
		if (!isset($value)) {
			$value='';
		}
		if (!isset($types)) {
			$types='gpc';
		}
		for ($i=0; $i<strlen($types); $i++) {
			switch ($types[$i]) {
				case 'g':
					if ($array!='') {
						if (isset($_GET[$name][$array])) {
							return $_GET[$name][$array];
						}
					} else {
						if (isset($_GET[$name])) {
							return $_GET[$name];
						}
					}
					break;
				case 'p':
					if ($array!='') {
						if (isset($_POST[$name][$array])) {
							return $_POST[$name][$array];
						}
					} else {
						if (isset($_POST[$name])) {
							return $_POST[$name];
						}
					}
					break;
				case 'f':
					if ($array!='') {
						if (isset($_FILES[$name][$array])) {
							return $_FILES[$name][$array];
						}
					} else {
						if (isset($_FILES[$name])) {
							return $_FILES[$name];
						}
					}
					break;
				case 'c':
					if ($array!='') {
						if (isset($_COOKIE[$name][$array])) {
							return $_COOKIE[$name][$array];
						}
					} else {
						if (isset($_COOKIE[$name])) {
							return $_COOKIE[$name];
						}
					}
					break;
				case 's':
					if ($array!='') {
						if (isset($_SESSION[$name][$array])) {
							return $_SESSION[$name][$array];
						}
					} else {
						if (isset($_SESSION[$name])) {
							return $_SESSION[$name];
						}
					}
					break;
				case 'r':
					if ($array!='') {
						if (isset($_SERVER[$name][$array])) {
							return $_SERVER[$name][$array];
						}
					} else {
						if (isset($_SERVER[$name])) {
							return $_SERVER[$name];
						}
					}
					break;
			}
		}

		return $value;
	}

	public function _direct($url, $status='302') {
		$url=str_replace('&amp;', '&', $url);

		switch ($status) {
			case 301:
				$_header='HTTP/1.1 301 Moved Permanently';
				break;
			case 302:
			default:
				$_header='HTTP/1.1 302 Found';
				break;
		}

		if (!headers_sent()) {
			header($_header);
			header('Location: '.$url);
			header('Connection: close');
		} else {
			echo 'Header already sent.<br/>';
			echo 'Redirect is not possible!';
			echo '<br/><br/>'.$_header.'<br/>';
			echo 'Location: <a href="'.$url.'">'.$url.'</a><br/>';
			echo 'Connection: close';
		}
		die();
	}

	public function getLicense($license) {
		switch ($license) {
			case 'GNU General Public License':
				return str_replace(['<a name="', '<a ', '<a target="_blank" id="'], ['<a id="', '<a target="_blank" ', '<a id="'], file_get_contents(abs_path.'resources/license/gpl-3.0.html'));
				break;
			default:
				return 'license ('.$license.') not found';
				break;
		}

	}

	/**
	 *
	 * @return osW_Tool
	 */
	public static function getInstance() {
		return parent::getInstance();
	}

}

?>