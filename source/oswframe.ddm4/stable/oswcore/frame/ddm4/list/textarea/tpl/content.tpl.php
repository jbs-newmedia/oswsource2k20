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


if ($this->getListElementOption($element, 'show_output') === true) {
    $view_data[$this->getListElementValue($element, 'name')] = $view_data[$this->getListElementValue($element, 'name')];
} else {
    $count = strlen($view_data[$this->getListElementValue($element, 'name')]);
    if ($count === 1) {
        $t = strlen($view_data[$this->getListElementValue($element, 'name')]) . ' ' . $this->getListElementOption(
            $element,
            'text_char'
        );
    } else {
        $t = strlen($view_data[$this->getListElementValue($element, 'name')]) . ' ' . $this->getListElementOption(
            $element,
            'text_chars'
        );
    }

    if ($this->getListElementOption($element, 'show_dialog') === true) {
        $view_data[$this->getListElementValue(
            $element,
            'name'
        )] = '<a style="cursor:pointer;" onclick="openDDM4Dialog_' . $this->getName(
        ) . '(this);" pageTitle="' . $this->getListElementValue($element, 'title') . '" pageName="' . htmlspecialchars(
            $view_data[$this->getListElementValue($element, 'name')]
        ) . '">' . $t . '</a>';
    } else {
        $view_data[$this->getListElementValue($element, 'name')] = $t;
    }
}
