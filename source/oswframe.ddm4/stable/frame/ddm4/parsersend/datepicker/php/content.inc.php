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
$fields['element_title']=$this->getSendElementValue($element, 'title');
$this->setFilterElementStorage($element, $fields);
$this->setFilterErrorElementStorage($element, false);

if ($this->getSendElementOption($element, 'required')===true) {
	if ((strlen($this->getDoSendElementStorage($element))!=8)||($this->getDoSendElementStorage($element)=='00000000')) {
		$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_miss'), $this->getFilterElementStorage($element)));
		$this->setFilterErrorElementStorage($element, true);
	}
}

if (($this->getFilterErrorElementStorage($element)!==true)&&($this->getDoSendElementStorage($element)!='')&&($this->getDoSendElementStorage($element)!='00000000')) {
	if (checkdate(substr($this->getDoSendElementStorage($element), 4, 2), substr($this->getDoSendElementStorage($element), 6, 2), substr($this->getDoSendElementStorage($element), 0, 4))!==true) {
		$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_incorrect'), $this->getFilterElementStorage($element)));
		$this->setFilterErrorElementStorage($element, true);
	}
}

if (($this->getFilterErrorElementStorage($element)!==true)&&($this->getSendElementValidation($element, 'value_min')!='')) {
	if ($this->getDoSendElementStorage($element)<$this->getSendElementValidation($element, 'value_min')) {
		$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_tosmall'), $this->getFilterElementStorage($element)));
		$this->setFilterErrorElementStorage($element, true);
	}
}

if (($this->getFilterErrorElementStorage($element)!==true)&&($this->getSendElementValidation($element, 'value_max')!='')) {
	if ($this->getDoSendElementStorage($element)>$this->getSendElementValidation($element, 'value_max')) {
		$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_tobig'), $this->getFilterElementStorage($element)));
		$this->setFilterErrorElementStorage($element, true);
	}
}

if (($this->getFilterErrorElementStorage($element)!==true)&&($this->getSendElementValidation($element, 'preg')!='')) {
	if (!preg_match($this->getSendElementValidation($element, 'preg'), $this->getDoSendElementStorage($element))) {
		$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_regerror'), $this->getFilterElementStorage($element)));
		$this->setFilterErrorElementStorage($element, true);
	}
}

if (($this->getFilterErrorElementStorage($element)!==true)&&(is_array($this->getSendElementValidation($element, 'filter')))) {
	foreach ($this->getSendElementValidation($element, 'filter') as $filter=>$values) {
		if (($this->getFilterErrorElementStorage($element)!==true)) {
			$values['module']=$filter;
			$this->parseFilterSendElementPHP($element, $values);
		}
	}
}

?>