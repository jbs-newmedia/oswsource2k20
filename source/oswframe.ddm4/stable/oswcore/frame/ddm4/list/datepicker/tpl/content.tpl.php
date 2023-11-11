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


if (($view_data[$this->getListElementValue($element, 'name')] === '') || ($view_data[$this->getListElementValue(
    $element,
    'name'
)] === '00000000')
) {
    $view_data[$this->getListElementValue($element, 'name')] = '---';
} else {
    if ($this->getListElementOption($element, 'month_asname') === true) {
        $view_data[$this->getListElementValue($element, 'name')] = \osWFrame\Core\DateTime::strftime(
            str_replace('%m.', ' %B ', $this->getListElementOption($element, 'format')),
            mktime(
                12,
                0,
                0,
                substr($view_data[$this->getListElementValue($element, 'name')], 4, 2),
                substr($view_data[$this->getListElementValue($element, 'name')], 6, 2),
                substr($view_data[$this->getListElementValue($element, 'name')], 0, 4)
            )
        );
    } else {
        $view_data[$this->getListElementValue($element, 'name')] = \osWFrame\Core\DateTime::strftime(
            $this->getListElementOption($element, 'format'),
            mktime(
                12,
                0,
                0,
                substr($view_data[$this->getListElementValue($element, 'name')], 4, 2),
                substr($view_data[$this->getListElementValue($element, 'name')], 6, 2),
                substr($view_data[$this->getListElementValue($element, 'name')], 0, 4)
            )
        );
    }
}
