<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package oswFrame - packagemanager
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 *
 */

define('abs_path', str_replace('\\', '/', str_replace(basename(dirname(__FILE__)), '', dirname(__FILE__))));

define('serverlist', 'oswframe2k20');
define('package', 'tools.packagemanager');
define('release', 'stable');

include(abs_path.'resources/includes/header.inc.php');

osW_Tool::getInstance()->initTool(package, release);

osW_Tool_Server::getInstance()->readServerList(serverlist);
osW_Tool_Server::getInstance()->updatePackageList(serverlist);
#osW_Tool::getInstance()->update(serverlist);

// TOOL - BEGIN

$info=array();
$info['details']=array();
$info['list']=array();
$info['details']['count']=0;
foreach(osW_Tool_Server::getInstance()->getPackageList() as $serverlist => $server_packages) {
	if(count(osW_Tool_PackageManager::getInstance()->checkPackageList($server_packages))>0) {
		foreach(osW_Tool_PackageManager::getInstance()->checkPackageList($server_packages) as $package_name => $package_data) {
			if ($package_data['options']['update']==true) {
				$info['details']['count']++;

				if(isset($package_data['info']['name'])) {
					$package_data['name']=$package_data['info']['name'];
				} else {
					$package_data['name']=$package_data['package'];
				}

				if (!isset($info['list'][$serverlist])) {
					$info['list'][$serverlist]=array();
				}
				$info['list'][$serverlist][$package_data['package'].'-'.$package_data['release']]=array('name'=>$package_data['name'], 'package'=>$package_data['package'], 'release'=>$package_data['release'], 'version_installed'=>$package_data['version_installed'], 'version'=>$package_data['version']);
			}
		}
 	}
}

if ((isset($_GET['action']))&&($_GET['action']=='update_all')) {
	foreach ($info['list'] as $serverlist => $packages) {
		foreach ($packages as $package => $package_data) {
			osW_Tool::getInstance()->installPackage($package_data['package'], $package_data['release'], $serverlist);
		}
	}
	die(json_encode(array('msg'=>'ok')));
}

die(json_encode($info));

?>