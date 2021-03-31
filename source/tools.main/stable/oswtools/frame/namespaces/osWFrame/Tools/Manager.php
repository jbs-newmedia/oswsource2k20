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

				if (file_exists($file)) {
					$info=json_decode(file_get_contents($file), true);
					$this->packagelist[$current_serverlist][$key]['version_installed']=$info['info']['version'];
					$installed[$info['info']['package']]=true;
				} else {
					$this->packagelist[$current_serverlist][$key]['version_installed']='0.0';
				}

				if (!isset($package['info']['group'])||(!in_array($package['info']['group'], $this->getKeys()))||($package['package']=='tools.main')) {
					unset($this->packagelist[$current_serverlist][$key]);
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

			uasort($this->packagelist[$current_serverlist], ['this', 'comparePackageList']);
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
		#print_a($serverlist);
		#print_a($package);
		#print_a($release);

		return true;
	}

	/**
	 * @param string $serverlist
	 * @param string $package
	 * @param string $release
	 * @return bool
	 */
	public function removePackage(string $manager_serverlist, string $package, string $release):bool {
		$file=Frame\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'filelist'.DIRECTORY_SEPARATOR.$package.'-'.$release.'.json';
		if (Frame\Filesystem::existsFile($file)) {
			$filelist=json_decode(file_get_contents($file), true);
			krsort($filelist);
			if (count($filelist)>0) {
				foreach ($filelist as $entry=>$foo) {
					if (Frame\Filesystem::isFile(Frame\Settings::getStringVar('settings_framepath').$entry)) {
						print_a(Frame\Filesystem::delFile(Frame\Settings::getStringVar('settings_framepath').$entry));
						#	Frame\Filesystem::delFile(Frame\Settings::getStringVar('settings_framepath').$entry);
					}
					if (Frame\Filesystem::isDir(Frame\Settings::getStringVar('settings_framepath').$entry)) {
						print_a(Frame\Filesystem::delFile(Frame\Settings::getStringVar('settings_framepath').$entry));
						#	Frame\Filesystem::delDir(Frame\Settings::getStringVar('settings_framepath').$entry);
					}
				}
			}

			$this->createConfigureFile();
			$this->createHtAccessFile();

			if (count($filelist)>0) {
				foreach ($filelist as $entry=>$foo) {
					if (Frame\Filesystem::isFile(Frame\Settings::getStringVar('settings_framepath').$entry)) {
						print_a(Frame\Filesystem::delFile(Frame\Settings::getStringVar('settings_framepath').$entry));
						#	Frame\Filesystem::delFile(Frame\Settings::getStringVar('settings_framepath').$entry);
					}
					if (Frame\Filesystem::isDir(Frame\Settings::getStringVar('settings_framepath').$entry)) {
						print_a(Frame\Filesystem::delFile(Frame\Settings::getStringVar('settings_framepath').$entry));
						#	Frame\Filesystem::delDir(Frame\Settings::getStringVar('settings_framepath').$entry);
					}
				}
			}
		}

		return true;
	}

	/**
	 * @return object
	 */
	public function createConfigureFile():object {
		return $this;
	}

	/**
	 * @return object
	 */
	public function createHtAccessFile():object {
		return $this;
	}

}

?>