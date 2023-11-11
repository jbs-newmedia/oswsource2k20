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

if ($view_data[$this->getListElementValue($element, 'name')] === '') {
    $view_data[$this->getListElementValue($element, 'name')] = HTML::outputString(
        $this->getListElementOption($element, 'text_blank')
    );
} else {
    $view_data[$this->getListElementValue(
        $element,
        'name'
    )] = '<a target="_blank" href="' . $view_data[$this->getListElementValue($element, 'name')] . '">' . HTML::outputString(
        $this->getListElementOption($element, 'text_file_view')
    ) . '</a>';
}
