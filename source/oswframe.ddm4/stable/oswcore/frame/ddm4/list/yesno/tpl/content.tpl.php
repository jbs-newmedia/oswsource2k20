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

use osWFrame\Core\HTML;

if ((int)$view_data[$this->getListElementValue($element, 'name')] === 1) {
    $view_data[$this->getListElementValue($element, 'name')] = HTML::outputString(
        $this->getListElementOption($element, 'text_yes')
    );
} elseif ((int)$view_data[$this->getListElementValue($element, 'name')] === 0) {
    $view_data[$this->getListElementValue($element, 'name')] = HTML::outputString(
        $this->getListElementOption($element, 'text_no')
    );
} else {
    $view_data[$this->getListElementValue($element, 'name')] = HTML::outputString(
        $this->getListElementOption($element, 'text_blank')
    );
}
