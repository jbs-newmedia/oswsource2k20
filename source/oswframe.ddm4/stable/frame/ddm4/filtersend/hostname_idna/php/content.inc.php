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

if (strlen($this->getDoSendElementStorage($element))>0) {
	if (\osWFrame\Core\Filter::verifyHostnameIDNAPattern($this->getDoSendElementStorage($element))!==true) {
		$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_incorrect'), $this->getFilterElementStorage($element)));
		$this->setFilterErrorElementStorage($element, true);
	}
}

?>