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

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');

if (\osWFrame\Core\Settings::getAction()=='doadd') {
	$this->setDoAddElementStorage($element, osWFrame\Core\Settings::catchValue($element, '', 'p'));
	$this->setDoAddElementStorage($element.$this->getAddElementOption($element, 'temp_suffix'), osWFrame\Core\Settings::catchValue($element.$this->getAddElementOption($element, 'temp_suffix'), '', 'p'));

	if ((isset($_FILES[$element]))&&($_FILES[$element]['error']==0)) {
		if ($this->getDoAddElementStorage($element)!='') {
			\osWFrame\Core\Filesystem::unlink(\osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getDoAddElementStorage($element));
		}
		if ($this->getDoAddElementStorage($element.$this->getAddElementOption($element, 'temp_suffix'))!='') {
			\osWFrame\Core\Filesystem::unlink(\osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getDoAddElementStorage($element.$this->getAddElementOption($element, 'temp_suffix')));
		}

		$dir=str_replace('//', '/', osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getAddElementOption($element, 'file_dir').'/');
		$dir_tmp=str_replace('//', '/', osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getAddElementOption($element, 'file_dir').'/'.$this->getAddElementOption($element, 'file_dir_tmp').'/');

		$file_parts=pathinfo($_FILES[$element]['name']);

		if ($this->getAddElementOption($element, 'store_name')===true) {
			$this->setDoAddElementStorage($element.'_name', ($_FILES[$element]['name']));
			$this->addDataElement($element.'_name', ['module'=>'hidden', 'name'=>$this->getAddElementValue($element, 'name').'_name',]);
		}

		if ($this->getAddElementOption($element, 'store_type')===true) {
			$this->setDoAddElementStorage($element.'_type', ($_FILES[$element]['type']));
			$this->addDataElement($element.'_type', ['module'=>'hidden', 'name'=>$this->getAddElementValue($element, 'name').'_type',]);
		}

		if ($this->getAddElementOption($element, 'store_size')===true) {
			$this->setDoAddElementStorage($element.'_size', ($_FILES[$element]['size']));
			$this->addDataElement($element.'_size', ['module'=>'hidden', 'name'=>$this->getAddElementValue($element, 'name').'_size',]);
		}

		if ($this->getAddElementOption($element, 'store_md5')===true) {
			$this->setDoAddElementStorage($element.'_md5', hash_file('md5', $_FILES[$element]['tmp_name']));
			$this->addDataElement($element.'_md5', ['module'=>'hidden', 'name'=>$this->getAddElementValue($element, 'name').'_md5',]);
		}

		if ($this->getAddElementOption($element, 'store_sha1')===true) {
			$this->setDoAddElementStorage($element.'_sha1', hash_file('sha1', $_FILES[$element]['tmp_name']));
			$this->addDataElement($element.'_sha1', ['module'=>'hidden', 'name'=>$this->getAddElementValue($element, 'name').'_sha1',]);
		}

		$file_name='';
		switch ($this->getAddElementOption($element, 'file_name')) {
			case 'time+rand':
				$file_name=time().rand(100, 999).'.'.$file_parts['extension'];
				break;
			case 'name_rand':
				$file_name=$file_parts['filename'].'_'.rand(100, 999).'.'.$file_parts['extension'];
				break;
			case 'original':
				$file_name=$_FILES[$element]['name'];
				break;
			case 'md5':
				$file_name=hash_file('md5', $_FILES[$element]['tmp_name']).'.'.$file_parts['extension'];
				break;
			case 'sha1':
				$file_name=hash_file('sha1', $_FILES[$element]['tmp_name']).'.'.$file_parts['extension'];
				break;
			case 'shared_md5':
				$file_name=hash_file('md5', $_FILES[$element]['tmp_name']).'.'.$file_parts['extension'];
				$dir=str_replace('//', '/', \osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getAddElementOption($element, 'file_dir').'/'.substr($file_name, 0, 2).'/'.substr($file_name, 2, 2).'/');
				break;
			case 'shared_sha1':
				$file_name=hash_file('sha1', $_FILES[$element]['tmp_name']).'.'.$file_parts['extension'];
				$dir=str_replace('//', '/', \osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getAddElementOption($element, 'file_dir').'/'.substr($file_name, 0, 2).'/'.substr($file_name, 2, 2).'/');
				break;
			default:
				$file_name=$this->getAddElementOption($element, 'file_name');
				break;
		}

		$file=$dir.$file_name;
		$file_tmp=$dir_tmp.$file_name;
		\osWFrame\Core\Filesystem::makeDir($dir);
		\osWFrame\Core\Filesystem::changeDirmode($dir);
		\osWFrame\Core\Filesystem::makeDir($dir_tmp);
		\osWFrame\Core\Filesystem::changeDirmode($dir_tmp);
		move_uploaded_file($_FILES[$element]['tmp_name'], $file_tmp);
		\osWFrame\Core\Filesystem::changeFilemode($file_tmp);

		$this->setDoAddElementStorage($element, str_replace(\osWFrame\Core\Settings::getStringVar('settings_abspath'), '', $file));
		$this->setDoAddElementStorage($element.$this->getAddElementOption($element, 'temp_suffix'), str_replace(\osWFrame\Core\Settings::getStringVar('settings_abspath'), '', $file_tmp));
	} elseif ((isset($_FILES[$element]))&&($_FILES[$element]['error']==4)) {
	} else {
		if ($this->getAddElementOption($element, 'read_only')!==true) {
			$this->setFilterErrorElementStorage($element.'_upload_error', true);
		}
	}

	if (\osWFrame\Core\Settings::catchValue($element.$this->getAddElementOption($element, 'delete_suffix'), '', 'p')==1) {
		if ($this->getDoAddElementStorage($element)!='') {
			\osWFrame\Core\Filesystem::unlink(\osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getDoAddElementStorage($element));
			$this->setDoAddElementStorage($element, '');
		}
		if ($this->getDoAddElementStorage($element.$this->getAddElementOption($element, 'temp_suffix'))!='') {
			\osWFrame\Core\Filesystem::unlink(\osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getDoAddElementStorage($element.$this->getAddElementOption($element, 'temp_suffix')));
			$this->setDoAddElementStorage($element.$this->getAddElementOption($element, 'temp_suffix'), '');
		}
	}
}

?>