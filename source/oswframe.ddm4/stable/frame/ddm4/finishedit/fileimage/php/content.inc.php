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

if ($this->getDoEditElementStorage($element.$this->getEditElementOption($element, 'temp_suffix'))!='') {
	rename(\osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getDoEditElementStorage($element.$this->getEditElementOption($element, 'temp_suffix')), \osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getDoEditElementStorage($element));
	if (($this->getEditElementStorage($element)!='')&&($this->getEditElementStorage($element)!=$this->getDoEditElementStorage($element))) {
		if ($this->getGroupOption('enable_log')!==true) {
			\osWFrame\Core\Filesystem::unlink(\osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getEditElementStorage($element));
			$this->setEditElementStorage($element, '');
		}
	}
} elseif (\osWFrame\Core\Settings::catchValue($element.$this->getEditElementOption($element, 'delete_suffix'), '', 'p')==1) {
	if ($this->getDoEditElementStorage($element)!='') {
		if ($this->getGroupOption('enable_log')!==true) {
			\osWFrame\Core\Filesystem::unlink(\osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getDoEditElementStorage($element));
			$this->setDoEditElementStorage($element, '');
		}

		if ($this->getEditElementOption($element, 'store_name')===true) {
			$this->setDoEditElementStorage($element.'_name', '');
			$this->addDataElement($element.'_name', ['module'=>'hidden', 'name'=>$this->getEditElementValue($element, 'name').'_name',]);
		}

		if ($this->getEditElementOption($element, 'store_type')===true) {
			$this->setDoEditElementStorage($element.'_type', '');
			$this->addDataElement($element.'_type', ['module'=>'hidden', 'name'=>$this->getEditElementValue($element, 'name').'_type',]);
		}

		if ($this->getEditElementOption($element, 'store_size')===true) {
			$this->setDoEditElementStorage($element.'_size', 0);
			$this->addDataElement($element.'_size', ['module'=>'hidden', 'name'=>$this->getEditElementValue($element, 'name').'_size',]);
		}

		if ($this->getEditElementOption($element, 'store_md5')===true) {
			$this->setDoEditElementStorage($element.'_md5', '');
			$this->addDataElement($element.'_md5', ['module'=>'hidden', 'name'=>$this->getEditElementValue($element, 'name').'_md5',]);
		}

		if ($this->getEditElementOption($element, 'store_sha1')===true) {
			$this->setDoEditElementStorage($element.'_sha1', '');
			$this->addDataElement($element.'_sha1', ['module'=>'hidden', 'name'=>$this->getEditElementValue($element, 'name').'_sha1',]);
		}
	}
}

?>