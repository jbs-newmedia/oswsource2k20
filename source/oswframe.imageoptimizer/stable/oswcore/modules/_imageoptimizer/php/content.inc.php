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

use osWFrame\Core\ImageOptimizer;
use osWFrame\Core\Settings;

$osW_ImageOptimizer = new ImageOptimizer();
$osW_ImageOptimizer->getOutput(Settings::catchStringValue('file_name', '', 'g'));
