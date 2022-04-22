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
	if (($this->getDoAddElementStorage($element)!=='0')&&($this->getDoAddElementStorage($element)!=='1')) {
		$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_empty'), $fields));
		$this->setFilterErrorElementStorage($element, true);
	}
}

if (($this->getFilterErrorElementStorage($element)!==true)&&(is_array($this->getAddElementValidation($element, 'filter')))) {
	foreach ($this->getAddElementValidation($element, 'filter') as $filter=>$values) {
		if (($this->getFilterErrorElementStorage($element)!==true)) {
			$values['module']=$filter;
			if ((!isset($values['enabled']))||($values['enabled']===true)) {
				$this->parseFilterAddElementPHP($element, $values);
			}
		}
	}
}

?>