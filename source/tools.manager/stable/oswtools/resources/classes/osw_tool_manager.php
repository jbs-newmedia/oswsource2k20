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
class osW_Tool_Manager extends osW_Tool_Object {

	public $data=array();

	function __construct() {
	}

	function __destruct() {
	}

	public function checkPackageList($packagelist) {
		$installed=array();
		foreach ($packagelist as $key=>$package) {
			$file=abs_path.'resources/json/package/'.$package['package'].'-'.$package['release'].'.json';

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

			if (!isset($package['info']['group'])||(!in_array($package['info']['group'], array('tool')))) {
				unset($packagelist[$key]);
			}
		}

		foreach ($packagelist as $key=>$package) {
			$packagelist[$key]['options']=array();
			$packagelist[$key]['options']['install']=false;
			$packagelist[$key]['options']['update']=false;
			$packagelist[$key]['options']['remove']=false;
			$packagelist[$key]['options']['blocked']=false;
			if ($packagelist[$key]['version_installed']=='0.0') {
				if (!isset($installed[$packagelist[$key]['package']])) {
					$packagelist[$key]['options']['install']=true;
				}
			} elseif (osW_Tool::getInstance()->checkVersion($packagelist[$key]['version_installed'], $packagelist[$key]['version'])) {
				$packagelist[$key]['options']['update']=true;
				$packagelist[$key]['options']['remove']=true;
			} else {
				$packagelist[$key]['options']['remove']=true;
			}

			if (($package['package']=='tools.manager')||($package['package']=='tools.main')) {
				$packagelist[$key]['options']['remove']=false;
				$packagelist[$key]['options']['install']=false;
			}
		}

		uasort($packagelist, array($this,'comparePackageList'));
		return $packagelist;
	}

	public function comparePackageList($a, $b) {
		return strcmp(strtolower($a['key']), strtolower($b['key']));
	}

	public function removeCustomPackage($package) {
		osW_Tool::getInstance()->delTree(abs_path.$package);
	}

	/**
	 *
	 * @return osW_Tool_Manager
	 */
	public static function getInstance() {
		return parent::getInstance();
	}
}

?>