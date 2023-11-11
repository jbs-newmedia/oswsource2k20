<?php declare(strict_types=0);

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license MIT License
 *
 * @var \osWFrame\Core\Template $osW_Template
 *
 */

$Tool = new \osWFrame\Tools\Tool\GITManager('oswframe2k20', 'tools.gitmanager', 'stable');
if (\osWFrame\Core\Settings::getAction() === 'noupdate') {
    $Tool->blockUpdate($osW_Template->buildhrefLink('current', 'action=start'));
}
if (\osWFrame\Core\Settings::getAction() === 'update') {
    $Tool->installUpdate($osW_Template->buildhrefLink('current', 'action=start'));
}
if ($Tool->hasUpdate() === true) {
    $osW_Template->addJSCodeHead($Tool->getUpdateConfirm($osW_Template->buildhrefLink('current', 'action=update'), $osW_Template->buildhrefLink('current', 'action=noupdate')));
}

$Tool->addNavigationElement('start', ['action' => 'start', 'title' => 'Start', 'icon' => 'fa fa-home fa-fw']);
$Tool->addNavigationElement('more', ['title' => 'More', 'icon' => 'fas fa-cog fa-fw']);
$Tool->addNavigationElement('updatepackagelist', ['action' => 'updatepackagelist', 'title' => 'Update packagelist', 'icon' => 'fas fa-database fa-fw'], 'more');
$Tool->addNavigationElement('changelog', ['action' => 'changelog', 'title' => 'Changelog', 'icon' => 'fas fa-list fa-fw'], 'more');
$Tool->addNavigationElement('about', ['action' => 'about', 'title' => 'About', 'icon' => 'fas fa-info fa-fw'], 'more');
\osWFrame\Core\Settings::setAction($Tool->validateAction(\osWFrame\Core\Settings::getAction()));
$Tool->checkNavigation(\osWFrame\Core\Settings::getAction());

if (in_array(\osWFrame\Core\Settings::getAction(), ['about'], true)) {
    include \osWFrame\Core\Settings::getStringVar('settings_abspath') . 'resources' . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'about.inc.php';
} elseif (in_array(\osWFrame\Core\Settings::getAction(), ['changelog'], true)) {
    include \osWFrame\Core\Settings::getStringVar('settings_abspath') . 'resources' . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'changelog.inc.php';
} else {
    $Tool->loadPackages();
    $manager_package = '';
    if (in_array(\osWFrame\Tools\Helper::getDoAction(), ['install', 'update', 'remove'], true)) {
        $manager_package = \osWFrame\Core\Settings::catchStringValue('manager_package');
    }
    if (in_array(\osWFrame\Tools\Helper::getDoAction(), ['install', 'update'], true)) {
        $Tool->installPackage($manager_package);
        $Tool->loadPackages();
        \osWFrame\Core\Network::dieJSON($Tool->getPackages());
    }
    if (in_array(\osWFrame\Tools\Helper::getDoAction(), ['remove'], true)) {
        $Tool->removePackage($manager_package);
        $Tool->loadPackages();
        \osWFrame\Core\Network::dieJSON($Tool->getPackages());
    }
    $jsfiles = ['resources' . \DIRECTORY_SEPARATOR . 'js' . \DIRECTORY_SEPARATOR . 'tools.gitmanager.js'];
    $osW_Template->addTemplateJSFiles('head', $jsfiles);

    $Tool->getPackages();
}

$osW_Template->setVar('Tool', $Tool);
