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
$fields['element_title']=$this->getAddElementValue($element, 'title');
$this->setFilterElementStorage($element, $fields);
$this->setFilterErrorElementStorage($element, false);

if ($this->getAddElementOption($element, 'required')===true) {
	if ((strlen($this->getDoAddElementStorage($element))!=8)||($this->getDoAddElementStorage($element)=='00000000')) {
		$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_miss'), $this->getFilterElementStorage($element)));
		$this->setFilterErrorElementStorage($element, true);
	}
}

if (($this->getFilterErrorElementStorage($element)!==true)&&($this->getDoAddElementStorage($element)!='')&&($this->getDoAddElementStorage($element)!='00000000')) {
	if (checkdate(substr($this->getDoAddElementStorage($element), 4, 2), substr($this->getDoAddElementStorage($element), 6, 2), substr($this->getDoAddElementStorage($element), 0, 4))!==true) {
		$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_incorrect'), $this->getFilterElementStorage($element)));
		$this->setFilterErrorElementStorage($element, true);
	}
}

if (($this->getFilterErrorElementStorage($element)!==true)&&($this->getAddElementValidation($element, 'value_min')!='')) {
	if ($this->getDoAddElementStorage($element)<$this->getAddElementValidation($element, 'value_min')) {
		$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_tosmall'), $this->getFilterElementStorage($element)));
		$this->setFilterErrorElementStorage($element, true);
	}
}

if (($this->getFilterErrorElementStorage($element)!==true)&&($this->getAddElementValidation($element, 'value_max')!='')) {
	if ($this->getDoAddElementStorage($element)>$this->getAddElementValidation($element, 'value_max')) {
		$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_tobig'), $this->getFilterElementStorage($element)));
		$this->setFilterErrorElementStorage($element, true);
	}
}

if (($this->getFilterErrorElementStorage($element)!==true)&&($this->getAddElementValidation($element, 'preg')!='')) {
	if (!preg_match($this->getAddElementValidation($element, 'preg'), $this->getDoAddElementStorage($element))) {
		$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_regerror'), $this->getFilterElementStorage($element)));
		$this->setFilterErrorElementStorage($element, true);
	}
}

if (($this->getFilterErrorElementStorage($element)!==true)&&(is_array($this->getAddElementValidation($element, 'filter')))) {
	foreach ($this->getAddElementValidation($element, 'filter') as $filter=>$values) {
		if (($this->getFilterErrorElementStorage($element)!==true)) {
			$values['module']=$filter;
			$this->parseFilterAddElementPHP($element, $values);
		}
	}
}

?>