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

$Tool=new \osWFrame\Tools\Tool\LogBrowser('oswframe2k20', 'tools.logbrowser', 'stable');
if (\osWFrame\Core\Settings::getAction()=='noupdate') {
	$Tool->blockUpdate($osW_Template->buildhrefLink('current', 'action=start'));
}
if (\osWFrame\Core\Settings::getAction()=='update') {
	$Tool->installUpdate($osW_Template->buildhrefLink('current', 'action=start'));
}
if ($Tool->hasUpdate()===true) {
	$osW_Template->addJSCodeHead($Tool->getUpdateConfirm($osW_Template->buildhrefLink('current', 'action=update'), $osW_Template->buildhrefLink('current', 'action=noupdate')));
}

$Tool->addNavigationElement('start', ['action'=>'start', 'title'=>'Start', 'icon'=>'fa fa-home fa-fw']);
$Tool->addNavigationElement('more', ['title'=>'More', 'icon'=>'fas fa-cog fa-fw']);
$Tool->addNavigationElement('changelog', ['action'=>'changelog', 'title'=>'Changelog', 'icon'=>'fas fa-list fa-fw'], 'more');
$Tool->addNavigationElement('about', ['action'=>'about', 'title'=>'About', 'icon'=>'fas fa-info fa-fw'], 'more');
\osWFrame\Core\Settings::setAction($Tool->validateAction(\osWFrame\Core\Settings::getAction()));
$Tool->checkNavigation(\osWFrame\Core\Settings::getAction());

$Tool->setFluidNavigation(true);

if (in_array(\osWFrame\Core\Settings::getAction(), ['about'])) {
	include \osWFrame\Core\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'about.inc.php';
} elseif (in_array(\osWFrame\Core\Settings::getAction(), ['changelog'])) {
	include \osWFrame\Core\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'changelog.inc.php';
} else {
	$Tool->setFluidContent(true);

	$jsfiles=['resources'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'tools.logbrowser.js'];
	$osW_Template->addTemplateJSFiles('head', $jsfiles);

	$Tool->readLogDirs(\osWFrame\Core\Settings::getStringVar('settings_framepath').\osWFrame\Tools\Configure::getFrameConfigValue('debug_path'));
	$dir=\osWFrame\Core\Settings::catchStringGetValue('dir');
	$file=\osWFrame\Core\Settings::catchStringGetValue('file');
	$display=\osWFrame\Core\Settings::catchStringGetValue('display');
	if ($Tool->isDir($dir)===true) {
		$Tool->readLogFiles(\osWFrame\Core\Settings::getStringVar('settings_framepath').\osWFrame\Tools\Configure::getFrameConfigValue('debug_path').$dir);
		if ($Tool->isFile($file)===true) {
			$Tool->loadFile(\osWFrame\Core\Settings::getStringVar('settings_framepath').\osWFrame\Tools\Configure::getFrameConfigValue('debug_path').$dir, $file, $display);
		} else {
			$file='';
		}
	} else {
		$dir='';
		$file='';
	}

	$osW_Form=new \osWFrame\Core\Form();
	$osW_Template->setVar('osW_Form', $osW_Form);

	$osW_Template->setVar('curdir', $dir);
	$osW_Template->setVar('curfile', $file);
	$osW_Template->setVar('curdisplay', $display);
}

$osW_Template->setVar('Tool', $Tool);

?>