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

$this->setDoEditElementStorage($element, strtolower($this->getDoEditElementStorage($element)));

if (($this->getFilterErrorElementStorage($element)!==true)&&($this->getEditElementOption($element, 'required')===true)) {
	if ($this->getDoEditElementStorage($element)=='') {
		$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_empty'), $fields));
		$this->setFilterErrorElementStorage($element, true);
	}
}

if (($this->getFilterErrorElementStorage($element)!==true)&&($this->getDoEditElementStorage($element)!='')) {
	$hex_color=$this->getDoEditElementStorage($element);

	if (preg_match('/^#[a-f0-9]{3}$/i', $hex_color)) {
		$this->setDoEditElementStorage($element, '#'.substr($hex_color, 1, 1).substr($hex_color, 1, 1).substr($hex_color, 2, 1).substr($hex_color, 2, 1).substr($hex_color, 3, 1).substr($hex_color, 3, 1));
		$hex_color=$this->getDoEditElementStorage($element);
	}

	if (!preg_match('/^#[a-f0-9]{6}$/i', $hex_color)) {
		$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_incorrect'), $fields));
	}
}

?>