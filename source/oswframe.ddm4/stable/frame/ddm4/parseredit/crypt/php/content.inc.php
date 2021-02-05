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

if (strlen($this->getDoEditElementStorage($element))>0) {

	$fields=[];
	$fields['element']=$element;
	$fields['element_title']=$this->getEditElementValue($element, 'title');
	$this->setFilterElementStorage($element, $fields);
	$this->setFilterErrorElementStorage($element, false);

	if (($this->getFilterErrorElementStorage($element)!==true)&&($this->getEditElementValidation($element, 'length_min')!='')) {
		if ($this->getDoEditElementStorage($element)=='') {
			$fields['length_min']=$this->getEditElementValidation($element, 'length_min');
			$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_empty'), $fields));
			$this->setFilterErrorElementStorage($element, true);
		} elseif (strlen($this->getDoEditElementStorage($element))<$this->getEditElementValidation($element, 'length_min')) {
			$fields['length_min']=$this->getEditElementValidation($element, 'length_min');
			$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_toshort'), $fields));
			$this->setFilterErrorElementStorage($element, true);
		}
	}

	if (($this->getFilterErrorElementStorage($element)!==true)&&($this->getEditElementValidation($element, 'length_max')!='')) {
		if (strlen($this->getDoEditElementStorage($element))>$this->getEditElementValidation($element, 'length_max')) {
			$fields['length_max']=$this->getEditElementValidation($element, 'length_max');
			$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_tolong'), $fields));
			$this->setFilterErrorElementStorage($element, true);
		}
	}

	if (($this->getFilterErrorElementStorage($element)!==true)&&($this->getEditElementValidation($element, 'preg')!='')) {
		if (!preg_match($this->getEditElementValidation($element, 'preg'), $this->getDoEditElementStorage($element))) {
			$this->getTemplate()->Form()->addErrorMessage($element, $this->getGroupMessage('validation_element_regerror'));
			$this->setFilterErrorElementStorage($element, true);
		}
	}

	if (($this->getFilterErrorElementStorage($element)!==true)&&(is_array($this->getEditElementValidation($element, 'filter')))) {
		foreach ($this->getEditElementValidation($element, 'filter') as $filter=>$values) {
			if (($this->getFilterErrorElementStorage($element)!==true)) {
				$values['module']=$filter;
				$this->parseFilterEditElementPHP($element, $values);
			}
		}
	}
} else {
	$this->removeEditElementValue($element, 'name');
}

?>