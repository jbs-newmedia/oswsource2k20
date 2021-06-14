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
	$database_where_string='';
	$ddm_selector_array=$this->getGroupOption('selector', 'database');
	if (!empty($ddm_selector_array)) {
		$ar_values=[];
		foreach ($ddm_selector_array as $key => $value) {
			$ar_values[]=$this->getGroupOption('alias', 'database').'.'.$key.'='.$value;
		}
		$database_where_string.=' AND ('.implode(' AND ', $ar_values).')';
	}

	$QcheckData=self::getConnection();
	$QcheckData->prepare('SELECT :formdata_name: FROM :table: AS :alias: WHERE :formdata_name: LIKE :value: AND :name_index:!=:value_index: :where:');
	$QcheckData->bindTable(':table:', $this->getGroupOption('table', 'database'));
	$QcheckData->bindRaw(':alias:', $this->getGroupOption('alias', 'database'));
	$QcheckData->bindRaw(':name_index:', $this->getGroupOption('alias', 'database').'.'.$this->getGroupOption('index', 'database'));
	if ($this->getGroupOption('db_index_type', 'database')=='string') {
		$QcheckData->bindString(':value_index:', $this->getIndexElementStorage());
	} else {
		$QcheckData->bindInt(':value_index:', intval($this->getIndexElementStorage()));
	}
	$QcheckData->bindRaw(':formdata_name:', $this->getGroupOption('alias', 'database').'.'.$this->getEditElementValue($element, 'name'));
	$QcheckData->bindString(':value:', $this->getDoEditElementStorage($element));
	$QcheckData->bindRaw(':where:', $database_where_string);
	if ($QcheckData->exec()>0) {
		$this->getTemplate()->Form()->addErrorMessage($element, osWFrame\Core\StringFunctions::parseTextWithVars($this->getGroupMessage('validation_element_unique'), $this->getFilterElementStorage($element)));
		$this->setFilterErrorElementStorage($element, true);
	}
}

?>