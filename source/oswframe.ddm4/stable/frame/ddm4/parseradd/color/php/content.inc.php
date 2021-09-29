<?php

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

$fields=[];
$fields['element']=$element;
$fields['element_title']=$this->getAddElementValue($element, 'title');
$this->setFilterElementStorage($element, $fields);
$this->setFilterErrorElementStorage($element, false);

$this->setDoAddElementStorage($element, strtolower($this->getDoAddElementStorage($element)));

if (($this->getFilterErrorElementStorage($element)!==true)&&($this->getAddElementOption($element, 'required')===true)) {
	if ($this->getDoAddElementStorage($element)=='') {
		$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_empty'), $fields));
		$this->setFilterErrorElementStorage($element, true);
	}
}

if (($this->getFilterErrorElementStorage($element)!==true)&&($this->getDoAddElementStorage($element)!='')) {
	$hex_color=$this->getDoAddElementStorage($element);

	if (preg_match('/^#[a-f0-9]{3}$/i', $hex_color)) {
		$this->setDoAddElementStorage($element, '#'.substr($hex_color, 1,1).substr($hex_color, 1,1).substr($hex_color, 2,1).substr($hex_color, 2,1).substr($hex_color, 3,1).substr($hex_color, 3,1));
		$hex_color=$this->getDoAddElementStorage($element);
	}

	if (!preg_match('/^#[a-f0-9]{6}$/i', $hex_color)) {
		$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_incorrect'), $fields));
	}
}

?>