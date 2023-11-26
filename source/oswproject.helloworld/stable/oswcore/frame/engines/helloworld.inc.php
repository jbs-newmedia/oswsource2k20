<?php declare(strict_types=0);

/**
 * This file is part of the HelloWorld package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   HelloWorld
 * @link      https://oswframe.com
 * @license   MIT License
 */

use osWFrame\Core\Bootstrap5;
use osWFrame\Core\FontAwesome5;
use osWFrame\Core\jQuery3;
use osWFrame\Core\Navigation;
use osWFrame\Core\Network;
use osWFrame\Core\Settings;
use osWFrame\Core\Template;

Settings::setStringVar('frame_current_module', Settings::getStringVar('frame_default_module'));

$osW_Template = new Template();

$osW_jQuery3 = new jQuery3($osW_Template);
$osW_jQuery3->load();

$osW_Bootstrap5 = new Bootstrap5($osW_Template);
if ((Settings::getStringVar('project_theme') !== null) && (Settings::getStringVar('project_theme') !== '')) {
    $osW_Bootstrap5->setTheme(Settings::getStringVar('project_theme'));
}
if ((Settings::getStringVar('project_theme_color') !== null) && (Settings::getStringVar(
    'project_theme_color'
) !== '')
) {
    $osW_Bootstrap5->setCustom('blue', Settings::getStringVar('project_theme_color'));
}
if ((Settings::getStringVar('project_theme_font') !== null) && (Settings::getStringVar('project_theme_font') !== '')) {
    $osW_Bootstrap5->setCustom('font-family-sans-serif', Settings::getStringVar('project_theme_font'));
}
$osW_Bootstrap5->load();

$osW_FontAwesome5 = new FontAwesome5($osW_Template);
$osW_FontAwesome5->load();

Network::sendHeader('Content-Type: text/html; charset=utf-8');
$osW_Template->addVoidTag('base', [
    'href' => Settings::getStringVar('project_domain_full'),
]);
$osW_Template->addVoidTag('meta', [
    'charset' => 'utf-8',
]);
$osW_Template->addVoidTag('meta', [
    'http-equiv' => 'X-UA-Compatible',
    'content' => 'IE=edge',
]);
$osW_Template->addVoidTag('meta', [
    'name' => 'viewport',
    'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no',
]);

Settings::setStringVar('frame_current_module', Settings::getStringVar('frame_default_module'));

$file = Settings::getStringVar(
    'settings_abspath'
) . 'oswproject' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
    'frame_current_module'
) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content.inc.php';
$file_core = Settings::getStringVar(
    'settings_abspath'
) . 'oswcore' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
    'frame_current_module'
) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content.inc.php';
if (file_exists($file)) {
    include_once $file;

    Navigation::checkUrl();
} elseif (file_exists($file_core)) {
    include_once $file_core;

    Navigation::checkUrl();
} else {
    Settings::setStringVar('frame_current_module', Settings::getStringVar('errorlogger_module'));
    $_GET['error_status'] = 404;

    $file = Settings::getStringVar(
        'settings_abspath'
    ) . 'oswproject' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
        'frame_current_module'
    ) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content.inc.php';
    $file_core = Settings::getStringVar(
        'settings_abspath'
    ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
        'frame_current_module'
    ) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'content.inc.php';
    if (file_exists($file)) {
        include_once $file;
    } elseif (file_exists($file_core)) {
        include_once $file_core;
    }
}
