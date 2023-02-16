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

if (strlen($this->getDoEditElementStorage($element))>0) {
	$database_where_string='';
	$ddm_filter_array=$this->getGroupOption('filter', 'database');
	if (!empty($ddm_filter_array)) {
		$ddm_filter=[];
		foreach ($ddm_filter_array as $filter_data) {
			$ar_values=[];
			foreach ($filter_data as $filter_logic=>$filter_elements) {
				foreach ($filter_elements as $filter_element) {
					$ar_values[]=$this->getGroupOption('alias', 'database').'.'.$filter_element['key'].$filter_element['operator'].$filter_element['value'];
				}
			}
			$ddm_filter[]='('.implode(' '.strtoupper($filter_logic).' ', $ar_values).')';
		}
		$database_where_string.=' AND ('.implode(' OR ', $ddm_filter).')';
	}

	$QcheckData=self::getConnection($this->getGroupOption('connection', 'database'));
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