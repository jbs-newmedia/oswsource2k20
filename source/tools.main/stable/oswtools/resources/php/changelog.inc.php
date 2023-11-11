<?php declare(strict_types=0);

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license MIT License
 *
 * @var \osWFrame\Tools\Tool\CoreTool $Tool
 * @var \osWFrame\Core\Template $osW_Template
 */

$file = \osWFrame\Core\Settings::getStringVar('settings_abspath') . 'resources' . \DIRECTORY_SEPARATOR . 'json' . \DIRECTORY_SEPARATOR . 'changelog' . \DIRECTORY_SEPARATOR . $Tool->getPackage() . '-' . $Tool->getRelease() . '.json';
if (file_exists($file)) {
    $changelog = json_decode(file_get_contents($file), true);
} else {
    $changelog = [];
}

$osW_Template->setVar('changelog', $changelog);
