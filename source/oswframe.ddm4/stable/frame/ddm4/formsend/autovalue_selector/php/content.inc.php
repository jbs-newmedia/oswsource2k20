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

	if ($this->getSendElementOption($element, 'selector_use')===true) {
		$ddm_selector_array=$this->getGroupOption('selector', 'database');
		if (!empty($ddm_selector_array)) {
			$ar_values=[];
			foreach ($ddm_selector_array as $key => $value) {
				if (is_int($value)==true) {
					$ar_values[]=$this->getGroupOption('alias', 'database').'.'.$key.'='.$value;
				} else {
					$ar_values[]=$this->getGroupOption('alias', 'database').'.'.$key.'=\''.$value.'\'';
				}
			}
			$database_where_string.=' AND ('.implode(' AND ', $ar_values).')';
		}
	}

	if ($this->getSendElementOption($element, 'default_value')!='') {
		$database_where_string.=' AND ('.$this->getSendElementValue($element, 'name').'>='.$this->getSendElementOption($element, 'default_value').')';
	}

	$QcheckData=self::getConnection();
	$QcheckData->prepare('SELECT :formdata_name: FROM :table: AS :alias: WHERE 1 :where: ORDER BY :formdata_name: DESC LIMIT 1');
	$QcheckData->bindTable(':table:', $this->getGroupOption('table', 'database'));
	$QcheckData->bindRaw(':alias:', $this->getGroupOption('alias', 'database'));
	$QcheckData->bindRaw(':formdata_name:', $this->getGroupOption('alias', 'database').'.'.$this->getSendElementValue($element, 'name'));
	$QcheckData->bindRaw(':where:', $database_where_string);
	if ($QcheckData->exec()==1) {
		$result=$QcheckData->fetch();
		$this->setDoSendElementStorage($element, ($result[$this->getSendElementValue($element, 'name')]+1));
	} else {
		$this->setDoSendElementStorage($element, $this->getSendElementOption($element, 'default_value'));
	}
}

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');

?>