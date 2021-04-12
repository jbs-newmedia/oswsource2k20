<?php

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

namespace osWFrame\Tools;

use osWFrame\Core as Frame;
use osWFrame\Tools as Tools;

class Manager {

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
	private const CLASS_RELEASE_VERSION=0;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='beta';

	/**
	 * @var array
	 */
	private array $packagelist=[];

	/**
	 * @var array
	 */
	private array $installed_packages=[];

	/**
	 * @var array
	 */
	private array $keys=[];

	/**
	 * Server constructor.
	 */
	public function __construct() {

	}

	/**
	 * @param array $keys
	 * @return object
	 */
	public function setKeys(array $keys):object {
		$this->keys=$keys;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getKeys():array {
		return $this->keys;
	}

	/**
	 * @return object
	 */
	public function getServerPackageList():object {
		$this->packagelist=Server::getPackageList();

		return $this;
	}

	/**
	 * @return array
	 */
	public function getPackageList():array {
		return $this->packagelist;
	}

	/**
	 * @return object
	 */
	public function checkPackageList():object {
		foreach ($this->packagelist as $current_serverlist=>$server_packages) {
			$installed=[];
			foreach ($this->packagelist[$current_serverlist] as $key=>$package) {
				$file=Frame\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'package'.DIRECTORY_SEPARATOR.$package['package'].'-'.$package['release'].'.json';
				if (isset($package['info']['name'])) {
					$this->packagelist[$current_serverlist][$key]['key']=$package['info']['name'].'-'.$key;
				} else {
					$this->packagelist[$current_serverlist][$key]['key']=$key;
				}

				if (Frame\Filesystem::existsFile($file)) {
					$info=json_decode(file_get_contents($file), true);
					$this->packagelist[$current_serverlist][$key]['version_installed']=$info['info']['version'];
					$installed[$info['info']['package']]=true;
				} else {
					$this->packagelist[$current_serverlist][$key]['version_installed']='0.0';
				}

				if ($this->getKeys()!==[]) {
					if (!isset($package['info']['group'])||(!in_array($package['info']['group'], $this->getKeys()))||($package['package']=='tools.main')) {
						unset($this->packagelist[$current_serverlist][$key]);
					}
				}
			}
			foreach ($this->packagelist[$current_serverlist] as $key=>$package) {
				$this->packagelist[$current_serverlist][$key]['options']=[];
				$this->packagelist[$current_serverlist][$key]['options']['install']=false;
				$this->packagelist[$current_serverlist][$key]['options']['update']=false;
				$this->packagelist[$current_serverlist][$key]['options']['remove']=false;
				$this->packagelist[$current_serverlist][$key]['options']['blocked']=false;
				if ($this->packagelist[$current_serverlist][$key]['version_installed']=='0.0') {
					if (!isset($installed[$this->packagelist[$current_serverlist][$key]['package']])) {
						$this->packagelist[$current_serverlist][$key]['options']['install']=true;
					}
				} elseif (Helper::checkVersion($this->packagelist[$current_serverlist][$key]['version_installed'], $this->packagelist[$current_serverlist][$key]['version'])) {
					$this->packagelist[$current_serverlist][$key]['options']['update']=true;
					$this->packagelist[$current_serverlist][$key]['options']['remove']=true;
				} else {
					$this->packagelist[$current_serverlist][$key]['options']['remove']=true;
				}
			}

			uasort($this->packagelist[$current_serverlist], [$this, 'comparePackageList']);
		}

		return $this;
	}

	/**
	 * @param array $a
	 * @param array $b
	 * @return int
	 */
	public function comparePackageList(array $a, array $b):int {
		return strcmp(strtolower($a['key']), strtolower($b['key']));
	}

	/**
	 * @param string $serverlist
	 * @param string $package
	 * @param string $release
	 * @return ?array
	 */
	public function getPackageDetails(string $serverlist, string $package, string $release):?array {
		if ((isset($this->packagelist[$serverlist]))&&(isset($this->packagelist[$serverlist][$package.'-'.$release]))) {
			return $this->packagelist[$serverlist][$package.'-'.$release];
		}

		return null;
	}

	/**
	 * @param string $serverlist
	 * @param string $package
	 * @param string $release
	 * @return bool
	 */
	public function installPackage(string $serverlist, string $package, string $release):bool {
		if ($this->packagelist==[]) {
			$this->getServerPackageList();
			$this->checkPackageList();
		}
		$package_data=$this->getPackageDetails($serverlist, $package, $release);
		if ($package_data!==null) {
			if ((($package_data['options']['install']===true)||($package_data['options']['update']===true))&&($package_data['options']['blocked']!==true)&&(!isset($this->installed_packages[$serverlist.'.'.$package.'-'.$release]))) {
				if ($this->installPackageForce($serverlist, $package, $release)!==true) {
					$return=false;
				}
				$this->installed_packages[$serverlist.'.'.$package.'-'.$release]=['package'=>$package, 'release'=>$release, 'serverlist'=>$serverlist];
				$this->createConfigureFile();
				$this->createHtAccessFile();
			} else {
				$return=false;
			}
		} else {
			$return=false;
		}

		return true;
	}

	/**
	 * @param string $serverlist
	 * @param string $package
	 * @param string $release
	 * @return bool
	 */
	private function installPackageForce(string $serverlist, string $package, string $release):bool {
		$return=true;
		$server_data=Server::getConnectedServer($serverlist);
		if ((isset($server_data['connected']))&&($server_data['connected']===true)) {
			$package_checksum=Server::getUrlData($server_data['server_url'].'?action=get_checksum&package='.$package.'&release='.$release.'&version=0');
			$package_data=Server::getUrlData($server_data['server_url'].'?action=get_content&package='.$package.'&release='.$release.'&version=0');
			if ($package_checksum==sha1($package_data)) {
				$cache_name=md5($serverlist.'#'.$package.'#'.$release).'.zip';
				$file=Frame\Settings::getStringVar('settings_abspath').'.caches'.DIRECTORY_SEPARATOR.$cache_name;
				file_put_contents($file, $package_data);

				$Zip=new Frame\Zip($file);
				$Zip->unpackDir(Frame\Settings::getStringVar('settings_framepath'), Tools\Configure::getFrameConfigValue('settings_chmod_dir'), Tools\Configure::getFrameConfigValue('settings_chmod_file'));
				Frame\Filesystem::delFile($file);

				$json_file=Frame\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'package'.DIRECTORY_SEPARATOR.$package.'-'.$release.'.json';
				if (Frame\Filesystem::existsFile($json_file)) {
					$json_data=json_decode(file_get_contents($json_file), true);
					if (isset($json_data['required'])) {
						foreach ($json_data['required'] as $package=>$package_data) {
							$status=$this->installPackage($package_data['serverlist'], $package_data['package'], $package_data['release']);
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

	/**
	 * @param string $serverlist
	 * @param string $package
	 * @param string $release
	 * @return bool
	 */
	public function removePackage(string $serverlist, string $package, string $release):bool {
		$file=Frame\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'filelist'.DIRECTORY_SEPARATOR.$package.'-'.$release.'.json';
		if (Frame\Filesystem::existsFile($file)) {
			$filelist=json_decode(file_get_contents($file), true);
			krsort($filelist);
			if (count($filelist)>0) {
				foreach ($filelist as $entry=>$foo) {
					$entry=substr($entry, 1);
					if (Frame\Filesystem::isFile(Frame\Settings::getStringVar('settings_framepath').$entry)) {
						Frame\Filesystem::delFile(Frame\Settings::getStringVar('settings_framepath').$entry);
					}
					if ((Frame\Filesystem::isDir(Frame\Settings::getStringVar('settings_framepath').$entry.DIRECTORY_SEPARATOR))&&(Frame\Filesystem::isEmptyDir(Frame\Settings::getStringVar('settings_framepath').$entry.DIRECTORY_SEPARATOR))) {
						Frame\Filesystem::delDir(Frame\Settings::getStringVar('settings_framepath').$entry.DIRECTORY_SEPARATOR);
					}
				}
			}

			$this->createConfigureFile();
			$this->createHtAccessFile();

			if (count($filelist)>0) {
				foreach ($filelist as $entry=>$foo) {
					$entry=substr($entry, 1);
					if (Frame\Filesystem::isFile(Frame\Settings::getStringVar('settings_framepath').$entry)) {
						Frame\Filesystem::delFile(Frame\Settings::getStringVar('settings_framepath').$entry);
					}
					if ((Frame\Filesystem::isDir(Frame\Settings::getStringVar('settings_framepath').$entry.DIRECTORY_SEPARATOR))&&(Frame\Filesystem::isEmptyDir(Frame\Settings::getStringVar('settings_framepath').$entry.DIRECTORY_SEPARATOR))) {
						Frame\Filesystem::delDir(Frame\Settings::getStringVar('settings_framepath').$entry.DIRECTORY_SEPARATOR);
					}
				}
			}
		}

		return true;
	}

	/**
	 * @return array
	 */
	public function getInstalledPackages():array {
		return $this->installed_packages;
	}

	/**
	 * @return object
	 */
	public function createConfigureFile():object {
		$dir=Frame\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'configure'.DIRECTORY_SEPARATOR;
		$configure=[];
		if (Frame\Filesystem::isDir($dir)) {
			foreach (glob($dir.'*.json') as $file) {
				$configure[str_replace($dir, '', $file)]['configure']=json_decode(file_get_contents($file), true);
			}
		}

		$configure_file_php=Frame\Settings::getStringVar('settings_framepath').'frame'.DIRECTORY_SEPARATOR.'configure.php';
		if ($configure!=[]) {
			$configure_output=[];
			$configure_output[]='';
			$configure_output[]='';
			$configure_output[]='/**';
			$configure_output[]=' * This file is part of the osWFrame package';
			$configure_output[]=' *';
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

			$vars=[];
			$vars['error_reporting_E_ALL']=E_ALL;
			$vars['error_reporting_E_ERROR']=E_ERROR;
			$vars['error_reporting_E_WARNING']=E_WARNING;
			$vars['error_reporting_E_PARSE']=E_PARSE;
			$vars['error_reporting_E_NOTICE']=E_NOTICE;
			foreach (['top', 'topmiddle', 'middle', 'middlebottom', 'bottom'] as $part) {
				foreach ($configure as $configure_file=>$configure_data) {
					if (isset($configure_data['configure']['configure'][$part])) {
						$configure_output[]='/* config-'.$part.' '.substr($configure_file, 0, -5).' */';
						foreach ($configure_data['configure']['configure'][$part] as $key=>$value) {
							$vars[$key]=$value;
							$premod=0;
							if (is_array($value)) {
								$configure_output[]='osW_setVar(\''.$key.'\', '.json_encode($value).');';
							} else {
								if (substr($value, 0, 3)=='###') {
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

				if (Frame\Filesystem::existsFile($configure_file_php)) {
					$configure_content=file_get_contents($configure_file_php);
					if (sha1(preg_replace('/\* \@version ([0-9]{14}) \(created by osWTools\)/', '* blocked', $output))!=sha1(preg_replace('/\* \@version ([0-9]{14}) \(created by osWTools\)/', '* blocked', $configure_content))) {
						file_put_contents($configure_file_php, $output);
						Frame\MessageStack::addMessage('configure', 'success', ['msg'=>'file "frame/configure.php" updated successfully.']);
					} else {
						Frame\MessageStack::addMessage('configure', 'success', ['msg'=>'file "frame/configure.php" is up to date.']);
					}
				} else {
					file_put_contents($configure_file_php, $output);
					Frame\MessageStack::addMessage('configure', 'success', ['msg'=>'file "frame/configure.php" created successfully.']);
				}
			} elseif (Frame\Filesystem::existsFile($configure_file_php)) {
				Frame\Filesystem::delFile($configure_file_php);
				Frame\MessageStack::addMessage('configure', 'success', ['msg'=>'file "frame/configure.php" removed successfully.']);
			}
		} elseif (Frame\Filesystem::existsFile($configure_file_php)) {
			Frame\Filesystem::delFile($configure_file_php);
			Frame\MessageStack::addMessage('configure', 'success', ['msg'=>'file "frame/configure.php" removed successfully.']);
		}

		Frame\Filesystem::changeFilemode($configure_file_php, Configure::getFrameConfigValue('settings_chmod_file'));

		return $this;
	}

	/**
	 * @return object
	 */
	public function createHtAccessFile():object {
		$dir=Frame\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'configure'.DIRECTORY_SEPARATOR;
		$configure=[];
		if (Frame\Filesystem::isDir($dir)) {
			foreach (glob($dir.'*.json') as $file) {
				$configure[str_replace($dir, '', $file)]['configure']=json_decode(file_get_contents($file), true);
			}
		}

		$htaccess_file=Frame\Settings::getStringVar('settings_framepath').'.htaccess';
		if ($configure!=[]) {
			$configure_output=[];
			$configure_output[]='';
			$configure_output[]='# version '.date('YmdHis').' (created by osWTools) #';
			$configure_output[]='';
			$configure_output[]='RewriteEngine on';
			$configure_output[]='';

			$count_header=count($configure_output);

			$vars=Configure::getFrameConfig();
			foreach (['top', 'topmiddle', 'middle', 'middlebottom', 'bottom'] as $part) {
				foreach ($configure as $configure_file=>$configure_data) {
					if (isset($configure_data['configure']['htaccess'][$part])) {
						$configure_output[]='# htaccess-'.$part.' '.substr($configure_file, 0, -5).'-block begin #';
						foreach ($configure_data['configure']['htaccess'][$part] as $line) {
							foreach ($vars as $key=>$value) {
								if (!is_array($value)) {
									$line=str_replace('###$vars[\''.$key.'\']', $value, $line);
								}
							}
							foreach ($vars as $key=>$value) {
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

				if (Frame\Filesystem::existsFile($htaccess_file)) {
					$htaccess_content=file_get_contents($htaccess_file);
					if (sha1(preg_replace('/\# version ([0-9]{14}) \(created by osWTools\) \#/', '# blocked #', trim($output)))==sha1(preg_replace('/\# version ([0-9]{14}) \(created by osWTools\) \#/', '# blocked #', trim($htaccess_content)))) {
						Frame\MessageStack::addMessage('configure', 'success', ['msg'=>'file ".htaccess" is up to date.']);
					} else {
						if (preg_match('/# osWFrame .htaccess block begin #(.*)# osWFrame .htaccess block end #/Uis', $htaccess_content, $result)==1) {
							if (sha1(preg_replace('/\# version ([0-9]{14}) \(created by osWTools\) \#/', '# blocked #', trim(implode("\n", $configure_output))))==sha1(preg_replace('/\# version ([0-9]{14}) \(created by osWTools\) \#/', '# blocked #', trim($result[1])))) {
								Frame\MessageStack::addMessage('configure', 'success', ['msg'=>'file ".htaccess" is up to date.']);
							} else {
								file_put_contents($htaccess_file, str_replace($result[1],implode("\n", $configure_output)."\n", $htaccess_content));
								Frame\MessageStack::addMessage('configure', 'success', ['msg'=>'file ".htaccess" updated successfully.']);
							}
						} else {
							file_put_contents($htaccess_file, $output."\n\n".$htaccess_content);
							Frame\MessageStack::addMessage('configure', 'success', ['msg'=>'file ".htaccess" updated with osWFrame successfully.']);
						}
					}
				} else {
					file_put_contents($htaccess_file, $output);
					Frame\MessageStack::addMessage('configure', 'success', ['msg'=>'file ".htaccess" created successfully.']);
				}
			} elseif (Frame\Filesystem::existsFile($htaccess_file)) {
				Frame\Filesystem::delFile($htaccess_file);
				Frame\MessageStack::addMessage('configure', 'success', ['msg'=>'file ".htaccess" removed successfully.']);
			}
		} elseif (Frame\Filesystem::existsFile($htaccess_file)) {
			Frame\Filesystem::delFile($htaccess_file);
			Frame\MessageStack::addMessage('configure', 'success', ['msg'=>'file ".htaccess" removed successfully.']);
		}

		return $this;
	}

	/**
	 * @return object
	 */
	public function protectDirs():object {
		$dir=Frame\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'configure'.DIRECTORY_SEPARATOR;
		$configure=[];
		if (Frame\Filesystem::isDir($dir)) {
			foreach (glob($dir.'*.json') as $file) {
				$configure[str_replace($dir, '', $file)]['configure']=json_decode(file_get_contents($file), true);
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
					$protect_dirs['path'][]=Configure::getFrameConfigValue($value);
				}
			}

			if ($protect_dirs['path']!=[]) {
				foreach ($protect_dirs['path'] as $_dir) {
					if (substr($_dir, -1)==DIRECTORY_SEPARATOR) {
						$_dir=substr($_dir, 0, -1);
					}
					if (strpos($_dir, DIRECTORY_SEPARATOR)>0) {
						$_dirs=explode(DIRECTORY_SEPARATOR, $_dir);
						$cdir=Frame\Settings::getStringVar('settings_framepath');
						foreach ($_dirs as $udir) {
							$cdir.=$udir.DIRECTORY_SEPARATOR;
							if (Frame\Filesystem::isDir($cdir)!==true) {
								Frame\MessageStack::addMessage('configure', 'success', ['msg'=>'directory "'.$_dir.'" created successfully.']);
								Frame\Filesystem::makeDir($cdir, Configure::getFrameConfigValue('settings_chmod_dir'));
							}
						}
					} else {
						$cdir=Frame\Settings::getStringVar('settings_framepath').$_dir.'/';
						if (Frame\Filesystem::isDir($cdir)!==true) {
							Frame\MessageStack::addMessage('configure', 'success', ['msg'=>'directory "'.$_dir.'" created successfully.']);
							Frame\Filesystem::makeDir($cdir, Configure::getFrameConfigValue('settings_chmod_dir'));
						}
					}
					$file=$cdir.'.htaccess';
					if (Frame\Filesystem::existsFile($file)!==true) {
						file_put_contents($file, "order deny,allow\ndeny from all");
						Frame\Filesystem::changeFilemode($file, Configure::getFrameConfigValue('settings_chmod_file'));
						Frame\MessageStack::addMessage('configure', 'success', ['msg'=>'directory "'.$_dir.'" protected successfully.']);
					}
				}
			}
		}

		return $this;
	}

}

?>