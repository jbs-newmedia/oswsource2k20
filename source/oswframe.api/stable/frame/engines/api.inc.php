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

if (\osWFrame\Core\Settings::getStringVar('database_server')!==null) {
	\osWFrame\Core\DB::addConnectionMYSQL(\osWFrame\Core\Settings::getStringVar('database_server'), \osWFrame\Core\Settings::getStringVar('database_username'), \osWFrame\Core\Settings::getStringVar('database_password'), \osWFrame\Core\Settings::getStringVar('database_db'), \osWFrame\Core\Settings::getStringVar('database_character'), 'default', \osWFrame\Core\Settings::getIntVar('database_port'));
	\osWFrame\Core\DB::connect();
}

$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.'api'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'content.inc.php';
if (file_exists($file)) {
	include_once $file;
}

?>