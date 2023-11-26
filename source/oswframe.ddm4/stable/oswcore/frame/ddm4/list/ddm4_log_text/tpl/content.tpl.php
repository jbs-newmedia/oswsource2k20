<?php declare(strict_types=0);

/**
 * This file is part of the osWFrame package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   osWFrame
 * @link      https://oswframe.com
 * @license   MIT License
 *
 * @var string $element
 * @var \osWFrame\Core\DDM4 $this
 * @var array $view_data
 *
 */

use osWFrame\Core\Filesystem;
use osWFrame\Core\Settings;

$file = Settings::getStringVar(
    'settings_abspath'
) . 'oswproject' . \DIRECTORY_SEPARATOR . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'list' . \DIRECTORY_SEPARATOR . $view_data['log_module'] . \DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR . 'log.tpl.php';
$file_core = Settings::getStringVar(
    'settings_abspath'
) . 'oswcore' . \DIRECTORY_SEPARATOR . 'frame' . \DIRECTORY_SEPARATOR . 'ddm4' . \DIRECTORY_SEPARATOR . 'list' . \DIRECTORY_SEPARATOR . $view_data['log_module'] . \DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR . 'log.tpl.php';
if (Filesystem::existsFile($file) === true) {
    include $file;
} elseif (Filesystem::existsFile($file_core) === true) {
    include $file_core;
} else {
    $view_data[$this->getListElementValue($element, 'name')] = $view_data[$this->getListElementValue($element, 'name')];
}
