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

$option=\osWFrame\Core\Settings::catchValue('option', '', 'g');

switch (strtolower($option)) {
	case 'single':
		osWFrame\Core\SmartOptimizer::getOutputSingle(\osWFrame\Core\Settings::catchValue('file_name', '', 'g'), 'css');
		break;
	default:
		osWFrame\Core\SmartOptimizer::getOutput(\osWFrame\Core\Settings::catchValue('file_name', '', 'g'), 'css');
		break;
}

?>