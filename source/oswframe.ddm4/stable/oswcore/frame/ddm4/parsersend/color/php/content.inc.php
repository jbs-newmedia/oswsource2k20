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

$fields = [];
$fields['element'] = $element;
$fields['element_title'] = $this->getSendElementValue($element, 'title');
$this->setFilterElementStorage($element, $fields);
$this->setFilterErrorElementStorage($element, false);

$this->setDoSendElementStorage($element, strtolower($this->getDoSendElementStorage($element)));

if (($this->getFilterErrorElementStorage($element) !== true) && ($this->getSendElementOption(
    $element,
    'required'
) === true)
) {
    if ($this->getDoSendElementStorage($element) === '') {
        $this->getTemplate()->Form()->addErrorMessage(
            $element,
            osWFrame\Core\StringFunctions::parseTextWithVars(
                $this->getGroupMessage('validation_element_empty'),
                $fields
            )
        );
        $this->setFilterErrorElementStorage($element, true);
    }
}

if (($this->getFilterErrorElementStorage($element) !== true) && ($this->getDoSendElementStorage($element) !== '')) {
    $hex_color = $this->getDoSendElementStorage($element);

    if (preg_match('/^#[a-f0-9]{3}$/i', $hex_color)) {
        $this->setDoSendElementStorage(
            $element,
            '#' . substr($hex_color, 1, 1) . substr($hex_color, 1, 1) . substr($hex_color, 2, 1) . substr(
                $hex_color,
                2,
                1
            ) . substr($hex_color, 3, 1) . substr($hex_color, 3, 1)
        );
        $hex_color = $this->getDoSendElementStorage($element);
    }

    if (!preg_match('/^#[a-f0-9]{6}$/i', $hex_color)) {
        $this->getTemplate()->Form()->addErrorMessage(
            $element,
            osWFrame\Core\StringFunctions::parseTextWithVars(
                $this->getGroupMessage('validation_element_incorrect'),
                $fields
            )
        );
    }
}
