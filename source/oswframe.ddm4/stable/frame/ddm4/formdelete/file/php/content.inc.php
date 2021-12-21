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

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');

if (\osWFrame\Core\Settings::getAction()=='dodelete') {
	$this->setDoDeleteElementStorage($element, osWFrame\Core\Settings::catchValue($element, '', 'p'));
	$this->setDoDeleteElementStorage($element.$this->getDeleteElementOption($element, 'temp_suffix'), osWFrame\Core\Settings::catchValue($element.$this->getDeleteElementOption($element, 'temp_suffix'), '', 'p'));

	if (($this->getDeleteElementOption($element, 'store_name')===true)||($this->getDeleteElementOption($element, 'store_type')===true)||($this->getDeleteElementOption($element, 'store_size')===true)||($this->getDeleteElementOption($element, 'store_md5')===true)||($this->getDeleteElementOption($element, 'store_sha1')===true)) {
		$Qselect=self::getConnection();
		$Qselect->prepare('SELECT :elements: FROM :table: AS :alias: WHERE :name_index:=:value_index:');
		$Qselect->bindRaw(':elements:', implode(', ', [$this->getGroupOption('alias', 'database').'.'.$element.'_name', $this->getGroupOption('alias', 'database').'.'.$element.'_type', $this->getGroupOption('alias', 'database').'.'.$element.'_size', $this->getGroupOption('alias', 'database').'.'.$element.'_md5', $this->getGroupOption('alias', 'database').'.'.$element.'_sha1']));
		$Qselect->bindTable(':table:', $this->getGroupOption('table', 'database'));
		$Qselect->bindRaw(':alias:', $this->getGroupOption('alias', 'database'));
		$Qselect->bindRaw(':name_index:', $this->getGroupOption('alias', 'database').'.'.$this->getGroupOption('index', 'database'));
		if ($this->getGroupOption('db_index_type', 'database')=='string') {
			$Qselect->bindString(':value_index:', $this->getIndexElementStorage());
		} else {
			$Qselect->bindInt(':value_index:', $this->getIndexElementStorage());
		}
		if ($Qselect->exec()==1) {
			$data_old=$Qselect->fetch();
		} else {
			$data_old=[];
			$data_old[$element.'_name']='';
			$data_old[$element.'_type']='';
			$data_old[$element.'_size']=0;
			$data_old[$element.'_md5']='';
			$data_old[$element.'_sha1']='';
		}
	}

	if ($this->getDeleteElementOption($element, 'store_name')===true) {
		if ($this->getDeleteElementStorage($element)!='') {
			$this->setDeleteElementStorage($element.'_name', $data_old[$element.'_name']);
		}
	}

	if ($this->getDeleteElementOption($element, 'store_type')===true) {
		if ($this->getDeleteElementStorage($element)!='') {
			$this->setDeleteElementStorage($element.'_type', $data_old[$element.'_type']);
		}
	}

	if ($this->getDeleteElementOption($element, 'store_size')===true) {
		if ($this->getDeleteElementStorage($element)!='') {
			$this->setDeleteElementStorage($element.'_size', $data_old[$element.'_size']);
		}
	}

	if ($this->getDeleteElementOption($element, 'store_md5')===true) {
		if ($this->getDeleteElementStorage($element)!='') {
			$this->setDeleteElementStorage($element.'_md5', $data_old[$element.'_md5']);
		}
	}

	if ($this->getDeleteElementOption($element, 'store_sha1')===true) {
		if ($this->getDeleteElementStorage($element)!='') {
			$this->setDeleteElementStorage($element.'_sha1', $data_old[$element.'_sha1']);
		}
	}

	if ($this->getDeleteElementOption($element, 'store_name')===true) {
		$this->setDoDeleteElementStorage($element.'_name', $data_old[$element.'_name']);
		$this->addDataElement($element.'_name', ['module'=>'hidden', 'name'=>$this->getDeleteElementValue($element, 'name').'_name',]);
	}

	if ($this->getDeleteElementOption($element, 'store_type')===true) {
		$this->setDoDeleteElementStorage($element.'_type', $data_old[$element.'_type']);
		$this->addDataElement($element.'_type', ['module'=>'hidden', 'name'=>$this->getDeleteElementValue($element, 'name').'_type',]);
	}

	if ($this->getDeleteElementOption($element, 'store_size')===true) {
		$this->setDoDeleteElementStorage($element.'_size', $data_old[$element.'_size']);
		$this->addDataElement($element.'_size', ['module'=>'hidden', 'name'=>$this->getDeleteElementValue($element, 'name').'_size',]);
	}

	if ($this->getDeleteElementOption($element, 'store_md5')===true) {
		$this->setDoDeleteElementStorage($element.'_md5', $data_old[$element.'_md5']);
		$this->addDataElement($element.'_md5', ['module'=>'hidden', 'name'=>$this->getDeleteElementValue($element, 'name').'_md5',]);
	}

	if ($this->getDeleteElementOption($element, 'store_sha1')===true) {
		$this->setDoDeleteElementStorage($element.'_sha1', $data_old[$element.'_sha1']);
		$this->addDataElement($element.'_sha1', ['module'=>'hidden', 'name'=>$this->getDeleteElementValue($element, 'name').'_sha1',]);
	}
}

?>