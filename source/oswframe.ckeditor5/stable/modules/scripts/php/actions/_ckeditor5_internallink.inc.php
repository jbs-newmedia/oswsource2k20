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

$loader=basename(\osWFrame\Core\Settings::catchStringValue('loader', '', 'gp'));

$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_default_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'actions'.DIRECTORY_SEPARATOR.'_ckeditor5_internallink'.DIRECTORY_SEPARATOR.$loader.'.inc.php';
if (file_exists($file)) {
	require_once $file;
}

?>