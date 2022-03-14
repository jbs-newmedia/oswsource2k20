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

$Tool=new \osWFrame\Tools\Tool\ProjectVerify('oswframe2k20', 'tools.projectverify', 'stable');
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
$Tool->addNavigationElement('settings', ['action'=>'settings', 'title'=>'Settings', 'icon'=>'fas fa-cogs fa-fw'], 'more');
$Tool->addNavigationElement('changelog', ['action'=>'changelog', 'title'=>'Changelog', 'icon'=>'fas fa-list fa-fw'], 'more');
$Tool->addNavigationElement('about', ['action'=>'about', 'title'=>'About', 'icon'=>'fas fa-info fa-fw'], 'more');
\osWFrame\Core\Settings::setAction($Tool->validateAction(\osWFrame\Core\Settings::getAction()));
$Tool->checkNavigation(\osWFrame\Core\Settings::getAction());

if (in_array(\osWFrame\Core\Settings::getAction(), ['about'])) {
	include \osWFrame\Core\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'about.inc.php';
} elseif (in_array(\osWFrame\Core\Settings::getAction(), ['changelog'])) {
	include \osWFrame\Core\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'changelog.inc.php';
} elseif (in_array(\osWFrame\Core\Settings::getAction(), ['settings'])) {
	if (\osWFrame\Tools\Helper::getDoAction()=='doignore') {
		if ($Tool->addIgnore(\osWFrame\Core\Settings::catchStringPostValue('element'), \osWFrame\Core\Settings::catchStringPostValue('type'))===true) {
			\osWFrame\Core\SessionMessageStack::addMessage('session', 'success', ['msg'=>'"'.\osWFrame\Core\Settings::catchStringPostValue('element').'" were ignored in the future.']);
			\osWFrame\Core\Network::dieJSON(['status'=>true]);
		} else {
			\osWFrame\Core\SessionMessageStack::addMessage('session', 'success', ['msg'=>'"'.\osWFrame\Core\Settings::catchStringPostValue('element').'" could not be ignored.']);
			\osWFrame\Core\Network::dieJSON(['status'=>true]);
		}
	}

	if (\osWFrame\Tools\Helper::getDoAction()=='dosave') {
		if ($Tool->updateSettings(explode("\n", \osWFrame\Core\Settings::catchStringPostValue('projectverify_files')), explode("\n", \osWFrame\Core\Settings::catchStringPostValue('projectverify_dirs')))===true) {
			\osWFrame\Core\SessionMessageStack::addMessage('session', 'success', ['msg'=>'Settings saved successfully.']);
			\osWFrame\Core\Network::directHeader($osW_Template->buildhrefLink('current', 'action=settings'));
		} else {
			\osWFrame\Core\SessionMessageStack::addMessage('session', 'danger', ['msg'=>'Settings could not be saved.']);
			\osWFrame\Core\Network::directHeader($osW_Template->buildhrefLink('current', 'action=settings'));
		}
	}

	$osW_Form=new \osWFrame\Core\Form();
	$osW_Template->setVar('osW_Form', $osW_Form);
} else {
	if (\osWFrame\Tools\Helper::getDoAction()=='download') {
		$Tool->forceDownload();
	}

	$jsfiles=['resources'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'tools.projectverify.js'];
	$osW_Template->addTemplateJSFiles('head', $jsfiles);
}

$osW_Template->setVar('Tool', $Tool);

?>