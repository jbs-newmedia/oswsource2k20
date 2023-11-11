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

$loader = basename(Settings::catchStringValue('loader', '', 'gp'));

$file = Settings::getStringVar('settings_abspath') . 'modules' . \DIRECTORY_SEPARATOR . Settings::getStringVar(
    'frame_default_module'
) . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'actions' . \DIRECTORY_SEPARATOR . '_ckeditor5_internallink' . \DIRECTORY_SEPARATOR . $loader . '.inc.php';
if (file_exists($file)) {
    require_once $file;
}
