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

$Tool=new \osWFrame\Tools\Tool\ToolsManager('oswframe2k20', 'tools.toolsmanager', 'stable');
if ($Tool->hasUpdate()===true) {
	$osW_Template->addJSCodeHead($Tool->getUpdateConfirm($osW_Template->buildhrefLink('current', 'action=update')));
}
if (\osWFrame\Core\Settings::getAction()=='update') {
	$Tool->installUpdate($osW_Template->buildhrefLink('current', 'action=start'));
}

$Tool->addNavigationElement('start', ['action'=>'start', 'title'=>'Start', 'icon'=>'fa fa-home fa-fw']);
$Tool->addNavigationElement('more', ['title'=>'More', 'icon'=>'fas fa-cog fa-fw']);
$Tool->addNavigationElement('changelog', ['action'=>'changelog', 'title'=>'Changelog', 'icon'=>'fas fa-list fa-fw'], 'more');
$Tool->addNavigationElement('about', ['action'=>'about', 'title'=>'About', 'icon'=>'fas fa-info fa-fw'], 'more');
\osWFrame\Core\Settings::setAction($Tool->validateAction(\osWFrame\Core\Settings::getAction()));
$Tool->checkNavigation(\osWFrame\Core\Settings::getAction());

if (in_array(\osWFrame\Core\Settings::getAction(), ['about'])) {
	include \osWFrame\Core\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'about.inc.php';
} elseif (in_array(\osWFrame\Core\Settings::getAction(), ['changelog'])) {
	include \osWFrame\Core\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'changelog.inc.php';
} else {
	$Tool->loadTools();
	if (in_array(\osWFrame\Tools\Helper::getDoAction(), ['install', 'update', 'remove'])) {
		$manager_serverlist=\osWFrame\Core\Settings::catchStringValue('manager_serverlist');
		$manager_package=\osWFrame\Core\Settings::catchStringValue('manager_package');
		$manager_release=\osWFrame\Core\Settings::catchStringValue('manager_release');
	}
	if (in_array(\osWFrame\Tools\Helper::getDoAction(), ['install', 'update'])) {
		$Tool->installPackage($manager_serverlist, $manager_package, $manager_release);

		$return=[];
		\osWFrame\Core\Network::dieJSON($return);
	}
	if (in_array(\osWFrame\Tools\Helper::getDoAction(), ['remove'])) {
		$Tool->removePackage($manager_serverlist, $manager_package, $manager_release);
		\osWFrame\Core\Network::dieJSON([md5($manager_serverlist.'#'.$manager_package.'#'.$manager_release)=>$Tool->getPackageDetails($manager_serverlist, $manager_package, $manager_release)]);
	}

	/*

	if (($doaction=='install')||($doaction=='update')) {
		osW_Tool_Server::getInstance()->readServerList($sl);
		osW_Tool_Server::getInstance()->updatePackageList($sl);
		ob_clean();
		osW_Tool::getInstance()->installPackage($manager_package, $manager_release, $sl);

		$data=osW_Tool_Server::getInstance()->checkPackageList($packages[$sl]);
		$data_proceed=osW_Tool::getInstance()->getPackagesProceed();

		$return=array();
		foreach ($data_proceed as $block) {
			if (isset($data[$block['package'].'-'.$block['release']])) {
				$return[md5($block['serverlist'].'#'.$block['package'].'#'.$block['release'])]=$data[$block['package'].'-'.$block['release']];
			}
		}
		die(json_encode($return));
	}

	if ($doaction=='remove') {
		osW_Tool::getInstance()->removePackage($manager_package, $manager_release);
		ob_clean();
		$data=osW_Tool_Server::getInstance()->checkPackageList($packages[$sl]);
		die(json_encode(array(md5($sl.'#'.$manager_package.'#'.$manager_release)=>$data[$manager_package.'-'.$manager_release])));
	}


*/

	$jsfiles=['resources'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'tools.toolsmanager.js'];
	$osW_Template->addTemplateJSFiles('head', $jsfiles);

	$Tool->getTools();
	$Tool->setSL(\osWFrame\Core\Settings::catchStringValue('sl'));
}

$osW_Template->setVar('Tool', $Tool);

?>