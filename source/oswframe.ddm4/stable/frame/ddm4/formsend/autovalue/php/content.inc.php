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

if (\osWFrame\Core\Settings::getAction()=='dosend') {
	$database_where_string='';

	if ($this->getSendElementOption($element, 'filter_use')===true) {
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
	}

	if ($this->getSendElementOption($element, 'default_value')!='') {
		$database_where_string.=' AND ('.$this->getSendElementValue($element, 'name').'>='.$this->getSendElementOption($element, 'default_value').')';
	}

	$QcheckData=osW_Database::getInstance()->query('SELECT :formdata_name: FROM :table: AS :alias: WHERE 1 :where: ORDER BY :formdata_name: DESC LIMIT 1');
	$QcheckData->bindTable(':table:', $this->getGroupOption('table', 'database'));
	$QcheckData->bindRaw(':alias:', $this->getGroupOption('alias', 'database'));
	$QcheckData->bindRaw(':formdata_name:', $this->getGroupOption('alias', 'database').'.'.$this->getSendElementValue($element, 'name'));
	$QcheckData->bindRaw(':where:', $database_where_string);
	$QcheckData->execute();
	if ($QcheckData->numberOfRows()==1) {
		$QcheckData->next();
		$this->setDoSendElementStorage($element, ($QcheckData->result[$this->getSendElementValue($element, 'name')]+1));
	} else {
		$this->setDoSendElementStorage($element, $this->getSendElementOption($element, 'default_value'));
	}
}

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');

?>