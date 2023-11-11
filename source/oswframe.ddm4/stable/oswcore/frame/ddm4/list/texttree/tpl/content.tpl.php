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


$data_level = $this->getListElementOption($element, 'data_level');
$index_key = $this->getListElementOption($element, 'index_key');

if (isset($data_level[$view_data[$index_key]])) {
    if ($data_level[$view_data[$index_key]] === 0) {
        $view_data[$this->getListElementValue($element, 'name')] = $view_data[$this->getListElementValue(
            $element,
            'name'
        )];
    } else {
        $name = '';
        for ($i = 1; $i <= $data_level[$view_data[$index_key]]; $i++) {
            $name .= '&nbsp;&nbsp;';
        }
        $name .= '➥ ' . $view_data[$this->getListElementValue($element, 'name')];
        $view_data[$this->getListElementValue($element, 'name')] = $name;
    }
} else {
    $view_data[$this->getListElementValue($element, 'name')] = '#ERROR# ' . $view_data[$this->getListElementValue(
        $element,
        'name'
    )];
}
