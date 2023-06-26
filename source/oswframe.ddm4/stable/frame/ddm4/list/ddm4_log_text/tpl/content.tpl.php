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

$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'frame'.DIRECTORY_SEPARATOR.'ddm4'.DIRECTORY_SEPARATOR.'list'.DIRECTORY_SEPARATOR.$view_data['log_module'].DIRECTORY_SEPARATOR.'tpl'.DIRECTORY_SEPARATOR.'log.tpl.php';

if (\osWFrame\Core\Filesystem::existsFile($file)===true) {
	include $file;
} else {
	$view_data[$this->getListElementValue($element, 'name')]=$view_data[$this->getListElementValue($element, 'name')];
}

?>