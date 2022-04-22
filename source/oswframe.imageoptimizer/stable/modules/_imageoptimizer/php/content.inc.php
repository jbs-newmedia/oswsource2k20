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

$osW_ImageOptimizer=new \osWFrame\Core\ImageOptimizer();
$osW_ImageOptimizer->getOutput(\osWFrame\Core\Settings::catchStringValue('file_name', '', 'g'));

?>