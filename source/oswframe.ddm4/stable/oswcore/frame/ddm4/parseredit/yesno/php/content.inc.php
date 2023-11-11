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
$fields['element_title'] = $this->getEditElementValue($element, 'title');
$this->setFilterElementStorage($element, $fields);
$this->setFilterErrorElementStorage($element, false);

if ($this->getEditElementOption($element, 'required') === true) {
    if (($this->getDoEditElementStorage($element) !== '0') && ($this->getDoEditElementStorage($element) !== '1')) {
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

if (($this->getFilterErrorElementStorage($element) !== true) && (is_array(
    $this->getEditElementValidation($element, 'filter')
))
) {
    foreach ($this->getEditElementValidation($element, 'filter') as $filter => $values) {
        if ($this->getFilterErrorElementStorage($element) !== true) {
            $values['module'] = $filter;
            if ((!isset($values['enabled'])) || ($values['enabled'] === true)) {
                $this->parseFilterEditElementPHP($element, $values);
            }
        }
    }
}
