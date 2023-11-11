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

if (($this->getFilterErrorElementStorage($element) !== true) && (str_replace(
    ',',
    '.',
    (string)((float)($this->getDoSendElementStorage($element)))
) !== (string)($this->getDoSendElementStorage($element)))
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

if (($this->getFilterErrorElementStorage($element) !== true) && ($this->getSendElementValidation(
    $element,
    'length_min'
) !== '')
) {
    if (strlen($this->getDoSendElementStorage($element)) < $this->getSendElementValidation($element, 'length_min')) {
        $this->getTemplate()->Form()->addErrorMessage(
            $element,
            osWFrame\Core\StringFunctions::parseTextWithVars(
                $this->getGroupMessage('validation_element_toshort'),
                $this->getFilterElementStorage($element)
            )
        );
        $this->setFilterErrorElementStorage($element, true);
    }
}

if (($this->getFilterErrorElementStorage($element) !== true) && ($this->getSendElementValidation(
    $element,
    'length_max'
) !== '')
) {
    if (strlen($this->getDoSendElementStorage($element)) > $this->getSendElementValidation($element, 'length_max')) {
        $this->getTemplate()->Form()->addErrorMessage(
            $element,
            osWFrame\Core\StringFunctions::parseTextWithVars(
                $this->getGroupMessage('validation_element_tolong'),
                $this->getFilterElementStorage($element)
            )
        );
        $this->setFilterErrorElementStorage($element, true);
    }
}

if (($this->getFilterErrorElementStorage($element) !== true) && ($this->getSendElementValidation(
    $element,
    'value_min'
) !== '')
) {
    if ($this->getDoSendElementStorage($element) < $this->getSendElementValidation($element, 'value_min')) {
        $this->getTemplate()->Form()->addErrorMessage(
            $element,
            osWFrame\Core\StringFunctions::parseTextWithVars(
                $this->getGroupMessage('validation_element_tosmall'),
                $this->getFilterElementStorage($element)
            )
        );
        $this->setFilterErrorElementStorage($element, true);
    }
}

if (($this->getFilterErrorElementStorage($element) !== true) && ($this->getSendElementValidation(
    $element,
    'value_max'
) !== '')
) {
    if ($this->getDoSendElementStorage($element) > $this->getSendElementValidation($element, 'value_max')) {
        $this->getTemplate()->Form()->addErrorMessage(
            $element,
            osWFrame\Core\StringFunctions::parseTextWithVars(
                $this->getGroupMessage('validation_element_tobig'),
                $this->getFilterElementStorage($element)
            )
        );
        $this->setFilterErrorElementStorage($element, true);
    }
}

if (($this->getFilterErrorElementStorage($element) !== true) && ($this->getSendElementValidation(
    $element,
    'preg'
) !== '')
) {
    if (!preg_match($this->getSendElementValidation($element, 'preg'), $this->getDoSendElementStorage($element))) {
        $this->getTemplate()->Form()->addErrorMessage(
            $element,
            osWFrame\Core\StringFunctions::parseTextWithVars(
                $this->getGroupMessage('validation_element_regerror'),
                $this->getFilterElementStorage($element)
            )
        );
        $this->setFilterErrorElementStorage($element, true);
    }
}

if (($this->getFilterErrorElementStorage($element) !== true) && (is_array(
    $this->getSendElementValidation($element, 'filter')
))
) {
    foreach ($this->getSendElementValidation($element, 'filter') as $filter => $values) {
        if ($this->getFilterErrorElementStorage($element) !== true) {
            $values['module'] = $filter;
            if ((!isset($values['enabled'])) || ($values['enabled'] === true)) {
                $this->parseFilterSendElementPHP($element, $values);
            }
        }
    }
}
