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

if (\osWFrame\Core\Settings::getAction()=='dosend') {
	$this->setDoSendElementStorage($element, osWFrame\Core\Settings::catchValue($element, '', 'p'));
	$this->setDoSendElementStorage($element.$this->getSendElementOption($element, 'temp_suffix'), osWFrame\Core\Settings::catchValue($element.$this->getSendElementOption($element, 'temp_suffix'), '', 'p'));

	if ((isset($_FILES[$element]))&&($_FILES[$element]['error']==0)) {
		if ($this->getDoSendElementStorage($element)!='') {
			\osWFrame\Core\Filesystem::unlink(\osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getDoSendElementStorage($element));
		}
		if ($this->getDoSendElementStorage($element.$this->getSendElementOption($element, 'temp_suffix'))!='') {
			\osWFrame\Core\Filesystem::unlink(\osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getDoSendElementStorage($element.$this->getSendElementOption($element, 'temp_suffix')));
		}

		$dir=str_replace('//', '/', osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getSendElementOption($element, 'file_dir').'/');
		$dir_tmp=str_replace('//', '/', osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getSendElementOption($element, 'file_dir').'/'.$this->getSendElementOption($element, 'file_dir_tmp').'/');

		$file_parts=pathinfo($_FILES[$element]['name']);

		if ($this->getSendElementOption($element, 'store_name')===true) {
			$this->setDoSendElementStorage($element.'_name', ($_FILES[$element]['name']));
			$this->addSendElement($element.'_name', ['module'=>'hidden', 'name'=>$this->getSendElementValue($element, 'name').'_name',]);
		}

		if ($this->getSendElementOption($element, 'store_type')===true) {
			$this->setDoSendElementStorage($element.'_type', ($_FILES[$element]['type']));
			$this->addSendElement($element.'_type', ['module'=>'hidden', 'name'=>$this->getSendElementValue($element, 'name').'_type',]);
		}

		if ($this->getSendElementOption($element, 'store_size')===true) {
			$this->setDoSendElementStorage($element.'_size', ($_FILES[$element]['size']));
			$this->addSendElement($element.'_size', ['module'=>'hidden', 'name'=>$this->getSendElementValue($element, 'name').'_size',]);
		}

		if ($this->getSendElementOption($element, 'store_md5')===true) {
			$this->setDoSendElementStorage($element.'_md5', hash_file('md5', $_FILES[$element]['tmp_name']));
			$this->addSendElement($element.'_md5', ['module'=>'hidden', 'name'=>$this->getSendElementValue($element, 'name').'_md5',]);
		}

		if ($this->getSendElementOption($element, 'store_sha1')===true) {
			$this->setDoSendElementStorage($element.'_sha1', hash_file('sha1', $_FILES[$element]['tmp_name']));
			$this->addSendElement($element.'_sha1', ['module'=>'hidden', 'name'=>$this->getSendElementValue($element, 'name').'_sha1',]);
		}

		$file_name='';
		switch ($this->getSendElementOption($element, 'file_name')) {
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
				$dir=str_replace('//', '/', \osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getSendElementOption($element, 'file_dir').'/'.substr($file_name, 0, 2).'/'.substr($file_name, 2, 2).'/');
				break;
			case 'shared_sha1':
				$file_name=hash_file('sha1', $_FILES[$element]['tmp_name']).'.'.$file_parts['extension'];
				$dir=str_replace('//', '/', \osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getSendElementOption($element, 'file_dir').'/'.substr($file_name, 0, 2).'/'.substr($file_name, 2, 2).'/');
				break;
			default:
				$file_name=$this->getSendElementOption($element, 'file_name');
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

		$this->setDoSendElementStorage($element, str_replace(\osWFrame\Core\Settings::getStringVar('settings_abspath'), '', $file));
		$this->setDoSendElementStorage($element.$this->getSendElementOption($element, 'temp_suffix'), str_replace(\osWFrame\Core\Settings::getStringVar('settings_abspath'), '', $file_tmp));
	} elseif ((isset($_FILES[$element]))&&($_FILES[$element]['error']==4)) {
	} else {
		$this->setFilterErrorElementStorage($element.'_upload_error', true);
	}

	if (\osWFrame\Core\Settings::catchValue($element.$this->getSendElementOption($element, 'delete_suffix'), '', 'p')==1) {
		if ($this->getDoSendElementStorage($element)!='') {
			\osWFrame\Core\Filesystem::unlink(\osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getDoSendElementStorage($element));
			$this->setDoSendElementStorage($element, '');
		}
		if ($this->getDoSendElementStorage($element.$this->getSendElementOption($element, 'temp_suffix'))!='') {
			\osWFrame\Core\Filesystem::unlink(\osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getDoSendElementStorage($element.$this->getSendElementOption($element, 'temp_suffix')));
			$this->setDoSendElementStorage($element.$this->getSendElementOption($element, 'temp_suffix'), '');
		}
	}
}

?>