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

use osWFrame\Core\DB;
use osWFrame\Core\Settings;

Settings::setStringVar('frame_current_module', Settings::getStringVar('frame_default_module'));

if (Settings::getStringVar('database_server') !== null) {
    DB::addConnectionMYSQL(
        Settings::getStringVar('database_server'),
        Settings::getStringVar('database_username'),
        Settings::getStringVar('database_password'),
        Settings::getStringVar('database_db')
    );
    DB::connect();
}

$file = Settings::getStringVar('settings_abspath') . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
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
