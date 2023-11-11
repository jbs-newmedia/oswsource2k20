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

if (($this->getFilterErrorElementStorage($element) !== true) && ((string)((int)($this->getDoEditElementStorage(
    $element
))) !== (string)($this->getDoEditElementStorage($element)))
) {
    $this->getTemplate()->Form()->addErrorMessage(
        $element,
        osWFrame\Core\StringFunctions::parseTextWithVars(
            $this->getGroupMessage('validation_element_incorrect'),
            $fields
        )
    );
    $this->setFilterErrorElementStorage($element, true);
}

if (($this->getFilterErrorElementStorage($element) !== true) && ($this->getEditElementValidation(
    $element,
    'length_min'
) !== '')
) {
    if ($this->getDoEditElementStorage($element) === '') {
        $fields['length_min'] = $this->getEditElementValidation($element, 'length_min');
        $this->getTemplate()->Form()->addErrorMessage(
            $element,
            osWFrame\Core\StringFunctions::parseTextWithVars(
                $this->getGroupMessage('validation_element_empty'),
                $fields
            )
        );
        $this->setFilterErrorElementStorage($element, true);
    } elseif (strlen((string)$this->getDoEditElementStorage($element)) < $this->getEditElementValidation(
        $element,
        'length_min'
    )
    ) {
        $fields['length_min'] = $this->getEditElementValidation($element, 'length_min');
        $this->getTemplate()->Form()->addErrorMessage(
            $element,
            osWFrame\Core\StringFunctions::parseTextWithVars(
                $this->getGroupMessage('validation_element_toshort'),
                $fields
            )
        );
        $this->setFilterErrorElementStorage($element, true);
    }
}

if (($this->getFilterErrorElementStorage($element) !== true) && ($this->getEditElementValidation(
    $element,
    'length_max'
) !== '')
) {
    if (strlen((string)$this->getDoEditElementStorage($element)) > $this->getEditElementValidation(
        $element,
        'length_max'
    )
    ) {
        $fields['length_max'] = $this->getEditElementValidation($element, 'length_max');
        $this->getTemplate()->Form()->addErrorMessage(
            $element,
            osWFrame\Core\StringFunctions::parseTextWithVars(
                $this->getGroupMessage('validation_element_tolong'),
                $fields
            )
        );
        $this->setFilterErrorElementStorage($element, true);
    }
}

if (($this->getFilterErrorElementStorage($element) !== true) && ($this->getEditElementValidation(
    $element,
    'value_min'
) !== '')
) {
    if ($this->getDoEditElementStorage($element) < $this->getEditElementValidation($element, 'value_min')) {
        $fields['value_min'] = $this->getEditElementValidation($element, 'value_min');
        $this->getTemplate()->Form()->addErrorMessage(
            $element,
            osWFrame\Core\StringFunctions::parseTextWithVars(
                $this->getGroupMessage('validation_element_tosmall'),
                $fields
            )
        );
        $this->setFilterErrorElementStorage($element, true);
    }
}

if (($this->getFilterErrorElementStorage($element) !== true) && ($this->getEditElementValidation(
    $element,
    'value_max'
) !== '')
) {
    if ($this->getDoEditElementStorage($element) > $this->getEditElementValidation($element, 'value_max')) {
        $fields['value_max'] = $this->getEditElementValidation($element, 'value_max');
        $this->getTemplate()->Form()->addErrorMessage(
            $element,
            osWFrame\Core\StringFunctions::parseTextWithVars(
                $this->getGroupMessage('validation_element_tobig'),
                $fields
            )
        );
        $this->setFilterErrorElementStorage($element, true);
    }
}

if (($this->getFilterErrorElementStorage($element) !== true) && ($this->getEditElementValidation(
    $element,
    'preg'
) !== '')
) {
    if (!preg_match($this->getEditElementValidation($element, 'preg'), $this->getDoEditElementStorage($element))) {
        $this->getTemplate()->Form()->addErrorMessage(
            $element,
            osWFrame\Core\StringFunctions::parseTextWithVars(
                $this->getGroupMessage('validation_element_regerror'),
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
