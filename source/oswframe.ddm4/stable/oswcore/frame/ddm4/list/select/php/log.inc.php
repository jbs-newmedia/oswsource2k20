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
 * @var string $element_name
 * @var string $value_old
 * @var string $value_new
 * @var \osWFrame\Core\DDM4 $this
 */

use osWFrame\Core\HTML;

$data = $this->getEditElementOption($element_name, 'data');

if (isset($data[$value_old])) {
    $value_old = HTML::outputString($data[$value_old]);
}

if (isset($data[$value_new])) {
    $value_new = HTML::outputString($data[$value_new]);
}
