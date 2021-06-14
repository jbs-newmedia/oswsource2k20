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

if (strlen($this->getDoSendElementStorage($element))>0) {
	$QcheckData=self::getConnection();
	$QcheckData->prepare('SELECT :formdata_name: FROM :table: AS :alias: WHERE :formdata_name: LIKE :value:');
	$QcheckData->bindTable(':table:', $this->getGroupOption('table', 'database'));
	$QcheckData->bindRaw(':alias:', $this->getGroupOption('alias', 'database'));
	$QcheckData->bindRaw(':formdata_name:', $this->getSendElementValue($element, 'name'));
	$QcheckData->bindString(':value:', $this->getDoSendElementStorage($element));
	if ($QcheckData->exec()>0) {
		$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_unique'), $this->getFilterElementStorage($element)));
		$this->setFilterErrorElementStorage($element, true);
	}
}

?>