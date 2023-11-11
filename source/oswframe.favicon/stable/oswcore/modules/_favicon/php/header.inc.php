<?php declare(strict_types=0);

/**
 * This file is part of the osWFrame package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   osWFrame
 * @link      https://oswframe.com
 * @license   MIT License
 */

use osWFrame\Core\Settings;

if (Settings::getStringVar('favicon_module') === Settings::getStringVar('frame_default_module')) {
    Settings::setStringVar('frame_default_engine', 'favicon');
    Settings::setStringVar('frame_default_output', 'favicon');
    Settings::setBoolVar('session_enabled', false);
} else {
    Settings::setStringVar('project_default_module', Settings::getStringVar('favicon_module'));
    Settings::setStringVar('frame_default_module', Settings::getStringVar('favicon_module'));
    $file = Settings::getStringVar('settings_abspath') . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
        'project_default_module'
    ) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'header.inc.php';
    if (file_exists($file)) {
        require_once $file;
    }
}
