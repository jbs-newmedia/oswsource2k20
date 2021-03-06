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

if ((\osWFrame\Core\Settings::getStringVar('favicon_file')=='')||(\osWFrame\Core\Settings::getStringVar('favicon_file')=='favicon.ico')) {
	\osWFrame\Core\Network::sendHeader('Content-Type: image/vnd.microsoft.icon');
	echo file_get_contents(\osWFrame\Core\Settings::getStringVar('settings_abspath').'favicon.ico');
} else {
	#\osWFrame\Core\Settings::getStringVar('settings_abspath').
	$file=\osWFrame\Core\Settings::getStringVar('favicon_file');
	$sizes=\osWFrame\Core\Settings::getArrayVar('favicon_sizes');

	if (\osWFrame\Core\IconCreator::existsCache($file, $sizes)!==true) {
		$osW_IconCreator=new \osWFrame\Core\IconCreator($file, $sizes);
		$osW_IconCreator->writeCache($file, $sizes);
	}

	\osWFrame\Core\Network::sendHeader('Content-Type: image/vnd.microsoft.icon');
	echo \osWFrame\Core\IconCreator::readCache($file, $sizes);
}

?>