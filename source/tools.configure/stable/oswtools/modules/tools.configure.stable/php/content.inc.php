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

$Tool = new \osWFrame\Tools\Tool\Configure('oswframe2k20', 'tools.configure', 'stable');
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
$Tool->addNavigationElement('changelog', ['action' => 'changelog', 'title' => 'Changelog', 'icon' => 'fas fa-list fa-fw'], 'more');
$Tool->addNavigationElement('about', ['action' => 'about', 'title' => 'About', 'icon' => 'fas fa-info fa-fw'], 'more');
\osWFrame\Core\Settings::setAction($Tool->validateAction(\osWFrame\Core\Settings::getAction()));
$Tool->checkNavigation(\osWFrame\Core\Settings::getAction());

if (in_array(\osWFrame\Core\Settings::getAction(), ['about'], true)) {
    include \osWFrame\Core\Settings::getStringVar('settings_abspath') . 'resources' . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'about.inc.php';
} elseif (in_array(\osWFrame\Core\Settings::getAction(), ['changelog'], true)) {
    include \osWFrame\Core\Settings::getStringVar('settings_abspath') . 'resources' . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'changelog.inc.php';
} else {
    $osW_Form = new \osWFrame\Core\Form();
    $Tool->setForm($osW_Form);
    $Tool->initFiles();
    $Tool->setPage(\osWFrame\Core\Settings::catchIntPostValue('page'));

    if ((isset($_POST['next'])) && ($_POST['next'] === 'Next step')) {
        $_POST['next'] = 'next';
    }
    if ((isset($_POST['prev'])) && ($_POST['prev'] === 'Previous step')) {
        $_POST['prev'] = 'prev';
        $Tool->decPage();
    }

    if ((isset($_POST['next'])) && ($_POST['next'] === 'next')) {
        $Tool->loadValuesFromJSON();
        $Tool->validateFile();
        $Tool->validateFields();
        if ($osW_Form->hasErrorMessages() !== true) {
            $Tool->writeValuesToJSON();
            $Tool->incPage();
            $Tool->clearPage();
        }
    }

    if ($Tool->isLastPage() === true) {
        $Tool->writeConfigure();
    } else {
        $Tool->loadValuesFromJSON();
        $Tool->runFile();
    }

    $osW_Template->setVar('osW_Form', $osW_Form);
}

$osW_Template->setVar('Tool', $Tool);
