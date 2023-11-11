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
 *
 */

$format = $this->getDeleteElementOption($element, 'format');
$format = str_replace('%Y', 'yyyy', $format);
$format = str_replace('%y', 'yy', $format);
$format = str_replace('%d', 'dd', $format);
$format = str_replace('%m', 'mm', $format);

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');
