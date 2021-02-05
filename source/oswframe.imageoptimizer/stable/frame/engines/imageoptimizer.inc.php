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

$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules/'.\osWFrame\Core\Settings::getStringVar('frame_default_module').'/php/content.inc.php';
if (file_exists($file)) {
	include_once $file;
}

?>