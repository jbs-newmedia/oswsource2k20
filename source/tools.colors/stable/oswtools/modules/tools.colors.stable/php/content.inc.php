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

$Tool=new \osWFrame\Tools\Tool\Colors('oswframe2k20', 'tools.colors', 'stable');
if ($Tool->hasUpdate()===true) {
	$osW_Template->addJSCodeHead($Tool->getUpdateConfirm($osW_Template->buildhrefLink('current', 'action=update'), $osW_Template->buildhrefLink('current', 'action=noupdate')));
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
	$Tool->setColor(\osWFrame\Core\Settings::catchStringValue('type'));
}

$osW_Template->setVar('Tool', $Tool);

?>