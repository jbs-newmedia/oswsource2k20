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

$option = Settings::catchStringGetValue('option');
$v = Settings::catchIntGetValue('v');
if ($v > 0) {
    osWFrame\Core\SmartOptimizer::setTS($v);
}

switch (strtolower($option)) {
    case 'single':
        osWFrame\Core\SmartOptimizer::getOutputSingle(Settings::catchValue('file_name', '', 'g'), 'js');

        break;
    default:
        osWFrame\Core\SmartOptimizer::getOutput(Settings::catchValue('file_name', '', 'g'), 'js');

        break;
}
