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

if (\osWFrame\Core\Settings::getStringVar('favicon_module')==\osWFrame\Core\Settings::getStringVar('frame_default_module')) {
	\osWFrame\Core\Settings::setStringVar('frame_default_engine', 'favicon');
	\osWFrame\Core\Settings::setStringVar('frame_default_output', 'favicon');
	\osWFrame\Core\Settings::setBoolVar('session_enabled', false);
} else {
	\osWFrame\Core\Settings::setStringVar('project_default_module', \osWFrame\Core\Settings::getStringVar('favicon_module'));
	\osWFrame\Core\Settings::setStringVar('frame_default_module', \osWFrame\Core\Settings::getStringVar('favicon_module'));
	$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('project_default_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'header.inc.php';
	if (file_exists($file)) {
		require_once $file;
	}
}

?>