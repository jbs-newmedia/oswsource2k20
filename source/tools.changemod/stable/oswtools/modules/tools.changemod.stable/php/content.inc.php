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

$Tool=new \osWFrame\Tools\Tool\ChangeMod('oswframe2k20', 'tools.chmod', 'stable');
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

if (in_array(\osWFrame\Core\Settings::getAction(), ['about'])) {
	include \osWFrame\Core\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'about.inc.php';
} elseif (in_array(\osWFrame\Core\Settings::getAction(), ['changelog'])) {
	include \osWFrame\Core\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'changelog.inc.php';
} else {
	$osW_Form=new \osWFrame\Core\Form();

	$chmod_dirs=$Tool->readDirList(\osWFrame\Core\Settings::getStringVar('settings_framepath'))->getDirList();

	if (\osWFrame\Tools\Helper::getDoAction()=='dochange') {
		$chmod_directory=\osWFrame\Core\Settings::catchStringValue('chmod_directory');
		$chmod_files_select=\osWFrame\Core\Settings::catchStringValue('chmod_files_select');
		$chmod_directory_select=\osWFrame\Core\Settings::catchStringValue('chmod_directory_select');

		if ($chmod_directory=='') {
			$osW_Form->addErrorMessage('chmod_directory', 'Please choose a directory.');
		} elseif (!isset($chmod_dirs[$chmod_directory])) {
			$osW_Form->addErrorMessage('chmod_directory', 'Directory not found.');
		}

		if (!in_array($chmod_files_select, $Tool->getFileOptions())) {
			$osW_Form->addErrorMessage('chmod_files_select', 'Select correct value.');
		}

		if (!in_array($chmod_directory_select, $Tool->getDirOptions())) {
			$osW_Form->addErrorMessage('chmod_directory_select', 'Select correct value.');
		}

		if ($osW_Form->hasErrorMessages()===true) {
			\osWFrame\Tools\Helper::setDoAction('');
		} else {
			$cmd='cd '.\osWFrame\Core\Settings::getStringVar('settings_framepath').$chmod_dirs[$chmod_directory].'; find -type f -print0 | xargs -0 chmod '.intval($chmod_files_select).'; find -type d -print0 | xargs -0 chmod '.intval($chmod_directory_select).';';
			exec($cmd);
			\osWFrame\Core\MessageStack::addMessage('result', 'success', ['msg'=>'Access permissions are changed successfully.']);
		}
	}

	$osW_Template->setVar('osW_Form', $osW_Form);
}

$osW_Template->setVar('Tool', $Tool);

?>