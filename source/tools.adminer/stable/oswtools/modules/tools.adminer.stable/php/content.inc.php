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

if (\osWFrame\Core\Settings::getAction()=='adminer') {
	function adminer_object() {
		include_once \osWFrame\Core\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'adminer'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'plugin.php';
		foreach (glob(\osWFrame\Core\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'adminer'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'*.php') as $filename) {
			include_once $filename;
		}

		if (\osWFrame\Tools\Configure::getFrameConfigValue('database_server')!='') {
			$plugins=[new AdminerFrames(), new FillLoginForm('server', \osWFrame\Tools\Configure::getFrameConfigValue('database_server'), \osWFrame\Tools\Configure::getFrameConfigValue('database_username'), \osWFrame\Tools\Configure::getFrameConfigValue('database_password'), \osWFrame\Tools\Configure::getFrameConfigValue('database_db')), new AdminerTableHeaderScroll(),];
		} else {
			$plugins=[new AdminerFrames(), new AdminerTableHeaderScroll(),];
		}

		return new AdminerPlugin($plugins);
	}

	include_once \osWFrame\Core\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'adminer'.DIRECTORY_SEPARATOR.'adminer-4.8.0.php';
	\osWFrame\Core\Settings::dieScript();
}

$Tool=new \osWFrame\Tools\Tool\Adminer('oswframe2k20', 'tools.adminer', 'stable');
if (\osWFrame\Core\Settings::getAction()=='noupdate') {
	$Tool->blockUpdate($osW_Template->buildhrefLink('current', 'action=start'));
}
if (\osWFrame\Core\Settings::getAction()=='update') {
	$Tool->installUpdate($osW_Template->buildhrefLink('current', 'action=start'));
}
if ($Tool->hasUpdate()===true) {
	$osW_Template->addJSCodeHead($Tool->getUpdateConfirm($osW_Template->buildhrefLink('current', 'action=update'), $osW_Template->buildhrefLink('current', 'action=noupdate')));
}
$Tool->setFluidNavigation(true);
$Tool->addUsedSoftware('Adminer', 'https://www.adminer.org/', 'Database management in a single PHP file');

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
	$Tool->setFluidContent(true);
	$Tool->setVH(true);
}

$osW_Template->setVar('Tool', $Tool);

?>