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
	private static array $serverlist=[];

	/**
	 * @var array
	 */
	private static array $serverlist_connected=[];

	/**
	 * Server constructor.
	 */
	private function __construct() {

	}

	/**
	 * @param string $path
	 * @return array
	 */
	public static function scanTools(string $path=''):array {
		if ($path=='') {
			$path=Frame\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR;
		}
		$tools=[];
		foreach (scandir($path) as $node) {
			if ((substr($node, 0, 6)=='tools.')&&($node!='tools.main.stable')) {
				$tools[$node]=$node;
			}
		}

		return $tools;
	}

	/**
	 * @param string $path
	 * @return array
	 */
	public static function getTools(string $path='') {
		$tools=self::scanTools($path);

		$package_tools=[];

		foreach (Server::getPackageList() as $current_serverlist=>$server_packages) {
			$package_tools[$current_serverlist]=[];
			foreach (self::checkPackageList($server_packages) as $package_name=>$package_data) {
				$package=$package_data['package'].'.'.$package_data['release'];
				if (isset($tools[$package])) {
					if (isset($package_data['info']['name'])) {
						$package_tools[$current_serverlist][$package]=$package_data;
						unset($tools[$package]);
					}
				}
			}
		}

		if ($tools!=[]) {
			foreach ($tools as $package) {
				$file=Frame\Settings::getStringVar('settings_abspath').$package.DIRECTORY_SEPARATOR.'info.json';
				if (file_exists($file)) {
					$package_tools['custom'][$package]=json_decode(file_get_contents($file), true);
				} else {
					$package_tools['custom'][$package]=$package;
				}
			}
		}

		return $package_tools;
	}

	/**
	 * @param array $packagelist
	 * @return array
	 */
	public static function checkPackageList(array $packagelist):array {
		$installed=[];
		foreach ($packagelist as $key=>$package) {
			$file=Frame\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'package'.DIRECTORY_SEPARATOR.$package['package'].'-'.$package['release'].'.json';

			if (isset($package['info']['name'])) {
				$packagelist[$key]['key']=$package['info']['name'].'-'.$key;
			} else {
				$packagelist[$key]['key']=$key;
			}

			if (file_exists($file)) {
				$info=json_decode(file_get_contents($file), true);
				$packagelist[$key]['version_installed']=$info['info']['version'];
				$installed[$info['info']['package']]=true;
			} else {
				$packagelist[$key]['version_installed']='0.0';
			}

			if (!isset($package['info']['group'])||(!in_array($package['info']['group'], ['tool']))||($package['package']=='tools.main')) {
				unset($packagelist[$key]);
			}
		}

		foreach ($packagelist as $key=>$package) {
			$packagelist[$key]['options']=[];
			$packagelist[$key]['options']['install']=false;
			$packagelist[$key]['options']['update']=false;
			$packagelist[$key]['options']['remove']=false;
			$packagelist[$key]['options']['blocked']=false;
			if ($packagelist[$key]['version_installed']=='0.0') {
				if (!isset($installed[$packagelist[$key]['package']])) {
					$packagelist[$key]['options']['install']=true;
				}
			} elseif (Helper::checkVersion($packagelist[$key]['version_installed'], $packagelist[$key]['version'])) {
				$packagelist[$key]['options']['update']=true;
				$packagelist[$key]['options']['remove']=true;
			} else {
				$packagelist[$key]['options']['remove']=true;
			}
		}

		uasort($packagelist, ['self', 'comparePackageList']);

		return $packagelist;
	}

	public static function getHTUsers():array {
		$htpasswd_file=Frame\Settings::getStringVar('settings_abspath').'.htpasswd';

		$htusers=[];
		if (file_exists($htpasswd_file)) {
			$htpasswd=file($htpasswd_file);
			if (count($htpasswd)>0) {
				foreach ($htpasswd as $user) {
					if (strlen($user)>3) {
						$ar_user=explode(':', $user);
						if (count($ar_user)>=2) {
							$users[$ar_user[0]]=trim($user);
						}
					}
				}
			}
		} else {
			$htusers=[];
		}

		return $htusers;
	}

	/**
	 * @param array $a
	 * @param array $b
	 * @return int
	 */
	public static function comparePackageList(array $a, array $b):int {
		return strcmp(strtolower($a['key']), strtolower($b['key']));
	}


	/**
	 * @param string $serverlist
	 * @param string $package
	 * @param string $release
	 * @return bool
	 */
	public static function installPackage(string $serverlist, string $package, string $release):bool {
		print_a($serverlist);
		print_a($package);
		print_a($release);

		return true;
	}

}

?>