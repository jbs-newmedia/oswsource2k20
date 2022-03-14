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

if (\osWFrame\Core\Settings::getStringVar('database_server')!==null) {
	\osWFrame\Core\DB::addConnectionMYSQL(\osWFrame\Core\Settings::getStringVar('database_server'), \osWFrame\Core\Settings::getStringVar('database_username'), \osWFrame\Core\Settings::getStringVar('database_password'), \osWFrame\Core\Settings::getStringVar('database_db'));
	\osWFrame\Core\DB::connect();
}

$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules/'.\osWFrame\Core\Settings::getStringVar('frame_default_module').'/php/content.inc.php';
if (file_exists($file)) {
	include_once $file;
}

?>