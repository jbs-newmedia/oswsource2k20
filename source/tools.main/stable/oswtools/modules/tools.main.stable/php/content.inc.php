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

$Tool=new \osWFrame\Tools\Tool\Main('oswframe2k20', 'tools.main', 'stable');
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
$Tool->addNavigationElement('protecttools', ['action'=>'protecttools', 'title'=>'Protect tools', 'icon'=>'fas fa-sign-in-alt fa-fw'], 'more');
$Tool->addNavigationElement('framekey', ['action'=>'framekey', 'title'=>'Frame-Key', 'icon'=>'fas fa-key fa-fw'], 'more');
$Tool->addNavigationElement('changelog', ['action'=>'changelog', 'title'=>'Changelog', 'icon'=>'fas fa-list fa-fw'], 'more');
$Tool->addNavigationElement('about', ['action'=>'about', 'title'=>'About', 'icon'=>'fas fa-info fa-fw'], 'more');
\osWFrame\Core\Settings::setAction($Tool->validateAction(\osWFrame\Core\Settings::getAction()));
$Tool->checkNavigation(\osWFrame\Core\Settings::getAction());

if (in_array(\osWFrame\Core\Settings::getAction(), ['about'])) {
	include \osWFrame\Core\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'about.inc.php';
} elseif (in_array(\osWFrame\Core\Settings::getAction(), ['changelog'])) {
	include \osWFrame\Core\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'changelog.inc.php';
} elseif (in_array(\osWFrame\Core\Settings::getAction(), ['protecttools'])) {
	if (!in_array(\osWFrame\Tools\Helper::getDoAction(), ['new', 'donew', 'manage', 'domanage'])) {
		\osWFrame\Tools\Helper::setDoAction('manage');
	}

	$osW_Form=new \osWFrame\Core\Form();

	if (\osWFrame\Tools\Helper::getDoAction()=='donew') {
		$main_username=trim(\osWFrame\Core\Settings::catchStringValue('main_username'));
		$main_password=\osWFrame\Core\Settings::catchStringValue('main_password');
		$main_confirm_password=\osWFrame\Core\Settings::catchStringValue('main_confirm_password');

		if (strlen($main_username)<3) {
			$osW_Form->addErrorMessage('main_username', 'Username is too short.');
		}

		if (strlen($main_password)<8) {
			$osW_Form->addErrorMessage('main_password', 'Password is too short.');
		} elseif ($main_password!==$main_confirm_password) {
			$osW_Form->addErrorMessage('main_confirm_password', 'Password and confirmation does not match.');
		}

		if ($osW_Form->hasErrorMessages()===true) {
			\osWFrame\Tools\Helper::setDoAction('new');
		} else {
			$Tool->addHTUser($main_username, $main_password);
			$Tool->writeHTAccess();
			\osWFrame\Tools\Helper::setDoAction('manage');
			\osWFrame\Core\MessageStack::addMessage('result', 'success', ['msg'=>'.htaccess has been successfully updated.']);
		}
	}

	if (\osWFrame\Tools\Helper::getDoAction()=='domanage') {
		$remove=[];
		if ($Tool->getHTUsers()!==[]) {
			foreach ($Tool->getHTUsers() as $user=>$password) {
				if (isset($_POST['updtusers'][$user])) {
					$remove[]=$user;
				}
			}
		}
		$Tool->removeHTUsers($remove);
		$Tool->writeHTAccess();
		\osWFrame\Tools\Helper::setDoAction('manage');
		\osWFrame\Core\MessageStack::addMessage('result', 'success', ['msg'=>'.htaccess has been successfully updated.']);
	}

	$osW_Template->setVar('osW_Form', $osW_Form);

} elseif (in_array(\osWFrame\Core\Settings::getAction(), ['framekey'])) {

	$osW_Form=new \osWFrame\Core\Form();

	if (\osWFrame\Tools\Helper::getDoAction()=='donew') {
		$Tool->createNewFrameKey();
		\osWFrame\Tools\Server::getFrameKey(true);
	}

	if (\osWFrame\Tools\Helper::getDoAction()=='dochange') {
		$frame_key=\osWFrame\Core\Settings::catchStringPostValue('frame_key');
		if ($Tool->validateFrameKey($frame_key)!==true) {
			$osW_Form->addErrorMessage('frame_key', 'Frame-Key is not correct. 64 chars, 0-9a-zA-Z.');
		} else {
			$Tool->writeFrameKey($frame_key);
			\osWFrame\Core\MessageStack::addMessage('result', 'success', ['msg'=>'Frame-Key changed successfully.']);
		}
	}

	$osW_Template->setVar('osW_Form', $osW_Form);

} else {
	$jsfiles=['resources'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'tools.main.js'];
	$osW_Template->addTemplateJSFiles('head', $jsfiles);
}

$osW_Template->setVar('Tool', $Tool);

?>