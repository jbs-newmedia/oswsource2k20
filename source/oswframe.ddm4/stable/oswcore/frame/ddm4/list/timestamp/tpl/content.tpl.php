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

if ($this->getListElementOption($element, 'month_asname') === true) {
    $view_data[$this->getListElementValue($element, 'name')] = \osWFrame\Core\DateTime::strftime(
        str_replace('%m.', ' %B ', $this->getListElementOption($element, 'date_format')),
        $view_data[$this->getListElementValue($element, 'name')]
    ) . ' ' . HTML::outputString($this->getGroupMessage('text_clock'));
} else {
    $view_data[$this->getListElementValue($element, 'name')] = \osWFrame\Core\DateTime::strftime(
        $this->getListElementOption($element, 'date_format'),
        $view_data[$this->getListElementValue($element, 'name')]
    ) . ' ' . HTML::outputString($this->getGroupMessage('text_clock'));
}
