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

use osWFrame\Core\BaseStaticTrait;
use osWFrame\Core\Filesystem;
use osWFrame\Core\Settings;
use osWFrame\Tools\ZipGITManager;

class GITManager extends CoreTool {

	use BaseStaticTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=1;

	/**
	 * Minor-Version der Klasse.
	 */
	private const CLASS_MINOR_VERSION=2;

	/**
	 * Release-Version der Klasse.
	 */
	private const CLASS_RELEASE_VERSION=0;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	/**
	 * @var array
	 */
	protected array $packages=[];

	/**
	 * GITManager constructor.
	 *
	 * @param string $serverlist
	 * @param string $package
	 * @param string $release
	 */
	public function __construct(string $serverlist, string $package, string $release) {
		parent::__construct($serverlist, $package, $release);
	}

	/**
	 * @param array $conf
	 * @return array
	 */
	public function getGITInformation(array $conf):array {
		if (!isset($conf['info'])) {
			return [];
		}
		if (!isset($conf['info']['link'])) {
			return [];
		}
		if (!isset($conf['info']['git'])) {
			return [];
		}

		if (in_array($conf['info']['git'], ['github'])) {
			$host=$conf['info']['link'];
			if ((isset($conf['info']['user']))&&(isset($conf['info']['token']))) {
				$user=$conf['info']['user'];
				$token=$conf['info']['token'];
			}
			$useragent='Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0';

			$ch=curl_init($host);
			curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
			if ((isset($conf['connection']))&&(isset($conf['connection']['ssl_verifyhost']))) {
				if ($conf['connection']['ssl_verifyhost']===true) {
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
				} else {
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				}
			}
			if ((isset($conf['connection']))&&(isset($conf['connection']['ssl_verifypeer']))) {
				if ($conf['connection']['ssl_verifypeer']===true) {
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
				} else {
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				}
			}
			if (strlen($user)>0) {
				curl_setopt($ch, CURLOPT_USERPWD, $user.":".$token);
			}
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$return=curl_exec($ch);
			curl_close($ch);
			$json=json_decode($return, true);
			if (($json==false)||($json==null)) {
				return [];
			}

			return $json;
		}

		if (in_array($conf['info']['git'], ['gitlab'])) {
			$host=$conf['info']['link'];
			if (isset($conf['info']['token'])) {
				$token=$conf['info']['token'];
			}
			$useragent='Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0';

			$ch=curl_init($host);
			curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
			if ((isset($conf['connection']))&&(isset($conf['connection']['ssl_verifyhost']))) {
				if ($conf['connection']['ssl_verifyhost']===true) {
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
				} else {
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				}
			}
			if ((isset($conf['connection']))&&(isset($conf['connection']['ssl_verifypeer']))) {
				if ($conf['connection']['ssl_verifypeer']===true) {
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
				} else {
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				}
			}
			if (strlen($token)>0) {
				$headers=[];
				$headers[]='PRIVATE-TOKEN: '.$token;
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			}
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$return=curl_exec($ch);
			curl_close($ch);
			$json=json_decode($return, true);
			if (($json==false)||($json==null)) {
				return [];
			}

			return $json;
		}

		return [];
	}

	/**
	 * @param string $git
	 * @param string $host
	 * @param string $user
	 * @param string $token
	 * @param array $connection
	 * @return string
	 */
	public function downloadGITZip(string $git, string $host, string $user='', string $token='', array $connection=[]):string {
		if (in_array($git, ['github'])) {
			$useragent='Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0';

			$ch=curl_init();
			curl_setopt($ch, CURLOPT_URL, $host);
			curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			if ((isset($connection))&&(isset($connection['ssl_verifyhost']))) {
				if ($connection['ssl_verifyhost']===true) {
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
				} else {
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				}
			}
			if ((isset($connection))&&(isset($connection['ssl_verifypeer']))) {
				if ($connection['ssl_verifypeer']===true) {
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
				} else {
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				}
			}
			if (strlen($user)>0) {
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($ch, CURLOPT_USERPWD, $user.":".$token);
			}

			curl_setopt($ch, CURLOPT_URL, $host);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

			$return=curl_exec($ch);
			curl_close($ch);

			return $return;
		}
		if (in_array($git, ['gitlab'])) {
			$useragent='Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0';

			$ch=curl_init();
			curl_setopt($ch, CURLOPT_URL, $host);
			curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			if ((isset($connection))&&(isset($connection['ssl_verifyhost']))) {
				if ($connection['ssl_verifyhost']===true) {
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
				} else {
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				}
			}
			if ((isset($connection))&&(isset($connection['ssl_verifypeer']))) {
				if ($connection['ssl_verifypeer']===true) {
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
				} else {
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				}
			}
			if (strlen($token)>0) {
				$headers=[];
				$headers[]='PRIVATE-TOKEN: '.$token;
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			}

			curl_setopt($ch, CURLOPT_URL, $host);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

			$return=curl_exec($ch);
			curl_close($ch);

			return $return;
		}

		return '';
	}

	/**
	 * @param array $conf
	 * @return array
	 */
	public function getGITDetails(array $conf):array {
		$dir=Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'sources'.DIRECTORY_SEPARATOR.'gitmanager'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR;
		Filesystem::makeDir($dir);
		$file=$dir.$conf['filename'];
		$cache_data=[];
		if ((Filesystem::existsFile($file)!==true)||(Filesystem::getFileModTime($file)<(time()-60))) {
			$cache_data=$this->getGITInformation($conf);
			if ($cache_data!=[]) {
				file_put_contents($file, json_encode($cache_data));
			}
		} elseif (Filesystem::existsFile($file)===true) {
			$cache_data=json_decode(file_get_contents($file), true);
		}

		return $cache_data;
	}

	/**
	 * @return void
	 */
	public function loadPackages():void {
		$dir=Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'sources'.DIRECTORY_SEPARATOR.'gitmanager'.DIRECTORY_SEPARATOR;
		if (Filesystem::isDir($dir)) {
			foreach (glob($dir.'*.json') as $file) {
				$json=json_decode(file_get_contents($file), true);
				$json['filename']=basename($file);
				$json['index']=md5($json['filename']);

				if (isset($json['info'])) {
					$this->packages[$json['index']]=[];
					if (!isset($json['info']['name'])) {
						$json['info']['name']='-';
					}
					if (!isset($json['info']['git'])) {
						$json['info']['git']='-';
					}
					if (!isset($json['info']['release'])) {
						$json['info']['release']='stable';
					}
					if (!isset($json['info']['remote_path'])) {
						$json['info']['remote_path']='';
					}
					if (!isset($json['info']['local_path'])) {
						$json['info']['local_path']='';
					}

					$this->packages[$json['index']]['name']=$json['info']['name'];
					$this->packages[$json['index']]['git']=$json['info']['git'];
					$this->packages[$json['index']]['release']=$json['info']['release'];

					$dir=Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'sources'.DIRECTORY_SEPARATOR.'gitmanager'.DIRECTORY_SEPARATOR.'installed'.DIRECTORY_SEPARATOR;
					Filesystem::makeDir($dir);
					$installed_file=$dir.$json['filename'];
					$this->packages[$json['index']]['installed']='-';
					if (Filesystem::existsFile($installed_file)) {
						$_json=json_decode(file_get_contents($installed_file), true);
						if (isset($_json['version'])) {
							$this->packages[$json['index']]['installed']=$_json['version'];
						}
						if (isset($_json['files'])) {
							$this->packages[$json['index']]['files']=$_json['files'];
						} else {
							$this->packages[$json['index']]['files']=[];
						}
						if (isset($_json['directories'])) {
							$this->packages[$json['index']]['directories']=$_json['directories'];
						} else {
							$this->packages[$json['index']]['directories']=[];
						}
					} else {
						$this->packages[$json['index']]['files']=[];
						$this->packages[$json['index']]['directories']=[];
					}

					$this->packages[$json['index']]['available']='-';
					if (in_array($this->packages[$json['index']]['git'], ['github'])) {
						$git=$this->getGITDetails($json);

						foreach ($git as $_git) {
							if ($this->packages[$json['index']]['release']=='stable') {
								if (($_git['draft']==false)&&($_git['prerelease']==false)) {
									$this->packages[$json['index']]['available']=$_git['tag_name'];
									$this->packages[$json['index']]['zip']=$_git['zipball_url'];
									break;
								}
							}
							if ($this->packages[$json['index']]['release']=='prerelease') {
								if (($_git['draft']==false)&&($_git['prerelease']==true)) {
									$this->packages[$json['index']]['available']=$_git['tag_name'];
									$this->packages[$json['index']]['zip']=$_git['zipball_url'];
									break;
								}
							}
						}
					}

					if (in_array($this->packages[$json['index']]['git'], ['gitlab'])) {
						$git=$this->getGITDetails($json);

						foreach ($git as $_git) {
							if ($this->packages[$json['index']]['release']=='stable') {
								if ($_git['upcoming_release']==false) {
									$this->packages[$json['index']]['available']=$_git['tag_name'];
									foreach ($_git['assets']['sources'] as $source) {
										if ($source['format']=='zip') {
											$this->packages[$json['index']]['zip']=$source['url'];
											break;
										}
									}
									break;
								}
							}
							if ($this->packages[$json['index']]['release']=='prerelease') {
								if ($_git['upcoming_release']==true) {
									$this->packages[$json['index']]['available']=$_git['tag_name'];
									foreach ($_git['assets']['sources'] as $source) {
										if ($source['format']=='zip') {
											$this->packages[$json['index']]['zip']=$source['url'];
											break;
										}
									}
									break;
								}
							}
						}
					}

					$this->packages[$json['index']]['install']=false;
					$this->packages[$json['index']]['update']=false;
					$this->packages[$json['index']]['remove']=false;
					if (($this->packages[$json['index']]['installed']=='-')&&($this->packages[$json['index']]['available']!='-')) {
						$this->packages[$json['index']]['install']=true;
					}
					if (($this->packages[$json['index']]['installed']!='-')&&($this->packages[$json['index']]['available']!='-')) {
						$this->packages[$json['index']]['remove']=true;
						if (version_compare($this->packages[$json['index']]['installed'], $this->packages[$json['index']]['available'], '<')) {
							$this->packages[$json['index']]['update']=true;
						}
					}

					$this->packages[$json['index']]['json']=$json;
				}
			}
		}
	}

	/**
	 * @return array
	 */
	public function getPackages():array {
		return $this->packages;
	}

	/**
	 * @param string $link
	 * @param string $i
	 * @param array $package_data
	 * @return string
	 */
	public function outputOption(string $link, string $i, array $package_data):string {
		$output='';

		if ($package_data['install']==true) {
			$output.='<a title="Install" href="javascript:manager(\''.$i.'\', \''.$link.'\', \'install\', \''.$package_data['json']['index'].'\')" class="install btn btn-primary btn-xs"><i class="fas fa-plus fa-fw"></i></a>';
		} else {
			$output.='<a title="Install" href="javascript:manager(\''.$i.'\', \''.$link.'\', \'install\', \''.$package_data['json']['index'].'\')" class="install btn btn-primary btn-xs disabled"><i class="fas fa-plus fa-fw"></i></a>';
		}
		$output.=' ';
		if ($package_data['update']==true) {
			$output.='<a title="Update" href="javascript:manager(\''.$i.'\', \''.$link.'\', \'update\', \''.$package_data['json']['index'].'\')" class="update btn btn-primary btn-xs"><i class="fa fa-sync fa-fw"></i></a>';
		} else {
			$output.='<a title="Update" href="javascript:manager(\''.$i.'\', \''.$link.'\', \'update\', \''.$package_data['json']['index'].'\')" class="update btn btn-primary btn-xs disabled"><i class="fa fa-sync fa-fw"></i></a>';
		}
		$output.=' ';
		if ($package_data['remove']==true) {
			$output.='<a title="Remove" href="javascript:manager(\''.$i.'\', \''.$link.'\', \'remove\', \''.$package_data['json']['index'].'\')" class="remove btn btn-primary btn-xs"><i class="fa fa-times fa-fw"></i></a>';
		} else {
			$output.='<a title="Remove" href="javascript:manager(\''.$i.'\', \''.$link.'\', \'remove\', \''.$package_data['json']['index'].'\')" class="remove btn btn-primary btn-xs disabled"><i class="fa fa-times fa-fw"></i></a>';
		}

		return $output;
	}

	/**
	 * @param string $package
	 * @return bool
	 */
	public function installPackage(string $package):bool {
		if (isset($this->packages[$package])) {
			if (in_array($this->packages[$package]['git'], ['github'])) {
				$cache_name=$package.'.zip';
				$file=Settings::getStringVar('settings_abspath').Settings::getStringVar('cache_path').$cache_name;
				if (!isset($this->packages[$package]['json']['connection'])) {
					$this->packages[$package]['json']['connection']=[];
				}
				file_put_contents($file, $this->downloadGITZip($this->packages[$package]['git'], $this->packages[$package]['zip'], $this->packages[$package]['json']['info']['user'], $this->packages[$package]['json']['info']['token'], $this->packages[$package]['json']['connection']));

				$Zip=new ZipGITManager($file);
				$Zip->unpackGitDir($this->packages[$package]['json']['info']['local_path'], $this->packages[$package]['json']['info']['remote_path'], $this->packages[$package]['files'], $this->packages[$package]['directories']);

				Filesystem::delFile($file);

				$json=['name'=>$this->packages[$package]['name'], 'version'=>$this->packages[$package]['available'], 'release'=>$this->packages[$package]['release'], 'files'=>$Zip->getFiles(), 'directories'=>$Zip->getDirectories()];

				$dir=Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'sources'.DIRECTORY_SEPARATOR.'gitmanager'.DIRECTORY_SEPARATOR.'installed'.DIRECTORY_SEPARATOR;
				Filesystem::makeDir($dir);
				$file=$dir.$this->packages[$package]['json']['filename'];
				file_put_contents($file, json_encode($json));

				return true;
			}
			if (in_array($this->packages[$package]['git'], ['gitlab'])) {
				$cache_name=$package.'.zip';
				$file=Settings::getStringVar('settings_abspath').Settings::getStringVar('cache_path').$cache_name;
				if (!isset($this->packages[$package]['json']['connection'])) {
					$this->packages[$package]['json']['connection']=[];
				}
				file_put_contents($file, $this->downloadGITZip($this->packages[$package]['git'], $this->packages[$package]['zip'], $this->packages[$package]['json']['info']['user'], $this->packages[$package]['json']['info']['token'], $this->packages[$package]['json']['connection']));

				$Zip=new ZipGITManager($file);
				$Zip->unpackGitDir($this->packages[$package]['json']['info']['local_path'], $this->packages[$package]['json']['info']['remote_path'], $this->packages[$package]['files'], $this->packages[$package]['directories']);

				Filesystem::delFile($file);

				$json=['name'=>$this->packages[$package]['name'], 'version'=>$this->packages[$package]['available'], 'release'=>$this->packages[$package]['release'], 'files'=>$Zip->getFiles(), 'directories'=>$Zip->getDirectories()];

				$dir=Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'sources'.DIRECTORY_SEPARATOR.'gitmanager'.DIRECTORY_SEPARATOR.'installed'.DIRECTORY_SEPARATOR;
				Filesystem::makeDir($dir);
				$file=$dir.$this->packages[$package]['json']['filename'];
				file_put_contents($file, json_encode($json));

				return true;
			}
		}

		return false;
	}

	/**
	 * @param string $package
	 * @return bool
	 */
	public function removePackage(string $package):bool {
		if (isset($this->packages[$package])) {
			$local_path=Settings::getStringVar('settings_framepath');
			if ($this->packages[$package]['json']['info']['local_path']!='') {
				$local_path.=$this->packages[$package]['json']['info']['local_path'].DIRECTORY_SEPARATOR;
			}

			if ((isset($this->packages[$package]['files']))&&($this->packages[$package]['files']!=[])) {
				foreach ($this->packages[$package]['files'] as $file=>$foo) {
					Filesystem::unlink($local_path.$file);
				}
			}
			if ((isset($this->packages[$package]['directories']))&&($this->packages[$package]['directories']!=[])) {
				krsort($this->packages[$package]['directories']);
				foreach ($this->packages[$package]['directories'] as $directory=>$foo) {
					$result=Filesystem::scanDir($local_path.$directory);
					if (($result!=null)&&(count(Filesystem::scanDir($local_path.$directory))==2)) {
						Filesystem::delEmptyDir($local_path.$directory);
					}
				}
			}

			$dir=Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'sources'.DIRECTORY_SEPARATOR.'gitmanager'.DIRECTORY_SEPARATOR;
			Filesystem::delFile($dir.'installed'.DIRECTORY_SEPARATOR.$this->packages[$package]['json']['filename']);
			Filesystem::delFile($dir.'cache'.DIRECTORY_SEPARATOR.$this->packages[$package]['json']['filename']);
			#Filesystem::delFile($dir.$this->packages[$package]['json']['filename']);

			return true;
		}

		return false;
	}

}

?>