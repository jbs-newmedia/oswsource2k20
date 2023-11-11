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
 *
 */

if ($value_old === '1') {
    $value_old = \osWFrame\Core\HTML::outputString($this->getEditElementOption($element_name, 'text_yes'));
} else {
    $value_old = \osWFrame\Core\HTML::outputString($this->getEditElementOption($element_name, 'text_no'));
}

if ($value_new === '1') {
    $value_new = \osWFrame\Core\HTML::outputString($this->getEditElementOption($element_name, 'text_yes'));
} else {
    $value_new = \osWFrame\Core\HTML::outputString($this->getEditElementOption($element_name, 'text_no'));
}
