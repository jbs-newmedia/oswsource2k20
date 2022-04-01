<?php

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license MIT License
 */

$fields=[];
$fields['element']=$element;
$fields['element_title']=$this->getEditElementValue($element, 'title');
$this->setFilterElementStorage($element, $fields);
$this->setFilterErrorElementStorage($element, false);

if (($this->getFilterErrorElementStorage($element)!==true)&&(str_replace(',', '.', strval(floatval($this->getDoEditElementStorage($element))))!==strval($this->getDoEditElementStorage($element)))) {
	$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_incorrect'), $fields));
	$this->setFilterErrorElementStorage($element, true);
}

if (($this->getFilterErrorElementStorage($element)!==true)&&($this->getEditElementValidation($element, 'length_min')!='')) {
	if (strlen($this->getDoEditElementStorage($element))<$this->getEditElementValidation($element, 'length_min')) {
		$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_toshort'), $this->getFilterElementStorage($element)));
		$this->setFilterErrorElementStorage($element, true);
	}
}

if (($this->getFilterErrorElementStorage($element)!==true)&&($this->getEditElementValidation($element, 'length_max')!='')) {
	if (strlen($this->getDoEditElementStorage($element))>$this->getEditElementValidation($element, 'length_max')) {
		$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_tolong'), $this->getFilterElementStorage($element)));
		$this->setFilterErrorElementStorage($element, true);
	}
}

if (($this->getFilterErrorElementStorage($element)!==true)&&($this->getEditElementValidation($element, 'value_min')!='')) {
	if ($this->getDoEditElementStorage($element)<$this->getEditElementValidation($element, 'value_min')) {
		$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_tosmall'), $this->getFilterElementStorage($element)));
		$this->setFilterErrorElementStorage($element, true);
	}
}

if (($this->getFilterErrorElementStorage($element)!==true)&&($this->getEditElementValidation($element, 'value_max')!='')) {
	if ($this->getDoEditElementStorage($element)>$this->getEditElementValidation($element, 'value_max')) {
		$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_tobig'), $this->getFilterElementStorage($element)));
		$this->setFilterErrorElementStorage($element, true);
	}
}

if (($this->getFilterErrorElementStorage($element)!==true)&&($this->getEditElementValidation($element, 'preg')!='')) {
	if (!preg_match($this->getEditElementValidation($element, 'preg'), $this->getDoEditElementStorage($element))) {
		$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_regerror'), $this->getFilterElementStorage($element)));
		$this->setFilterErrorElementStorage($element, true);
	}
}

if (($this->getFilterErrorElementStorage($element)!==true)&&(is_array($this->getEditElementValidation($element, 'filter')))) {
	foreach ($this->getEditElementValidation($element, 'filter') as $filter=>$values) {
		if (($this->getFilterErrorElementStorage($element)!==true)) {
			$values['module']=$filter;
			if ((!isset($values['enabled']))||($values['enabled']===true)) {
				$this->parseFilterEditElementPHP($element, $values);
			}
		}
	}
}

?>