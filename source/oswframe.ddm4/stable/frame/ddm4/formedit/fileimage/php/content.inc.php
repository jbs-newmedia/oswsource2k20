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

if (\osWFrame\Core\Settings::getAction()=='doedit') {
	$this->setDoEditElementStorage($element, osWFrame\Core\Settings::catchValue($element, '', 'p'));
	$this->setDoEditElementStorage($element.$this->getEditElementOption($element, 'temp_suffix'), osWFrame\Core\Settings::catchValue($element.$this->getEditElementOption($element, 'temp_suffix'), '', 'p'));

	if ($this->getEditElementValue($element, 'name')!='') {
		if (($this->getEditElementOption($element, 'store_name')===true)||($this->getEditElementOption($element, 'store_type')===true)||($this->getEditElementOption($element, 'store_size')===true)||($this->getEditElementOption($element, 'store_md5')===true)||($this->getEditElementOption($element, 'store_sha1')===true)) {
			$Qselect=self::getConnection($this->getGroupOption('connection', 'database'));
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
	}

	if ($this->getEditElementOption($element, 'store_name')===true) {
		if ($this->getEditElementStorage($element)!='') {
			$this->setEditElementStorage($element.'_name', $data_old[$element.'_name']);
		}
	}

	if ($this->getEditElementOption($element, 'store_type')===true) {
		if ($this->getEditElementStorage($element)!='') {
			$this->setEditElementStorage($element.'_type', $data_old[$element.'_type']);
		}
	}

	if ($this->getEditElementOption($element, 'store_size')===true) {
		if ($this->getEditElementStorage($element)!='') {
			$this->setEditElementStorage($element.'_size', $data_old[$element.'_size']);
		}
	}

	if ($this->getEditElementOption($element, 'store_md5')===true) {
		if ($this->getEditElementStorage($element)!='') {
			$this->setEditElementStorage($element.'_md5', $data_old[$element.'_md5']);
		}
	}

	if ($this->getEditElementOption($element, 'store_sha1')===true) {
		if ($this->getEditElementStorage($element)!='') {
			$this->setEditElementStorage($element.'_sha1', $data_old[$element.'_sha1']);
		}
	}

	if ((isset($_FILES[$element]))&&($_FILES[$element]['error']==0)) {
		if ($this->getEditElementStorage($element)=='') {
			if ($this->getDoEditElementStorage($element)!='') {
				\osWFrame\Core\Filesystem::unlink(\osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getDoEditElementStorage($element));
			}
			if ($this->getDoEditElementStorage($element.$this->getEditElementOption($element, 'temp_suffix'))!='') {
				\osWFrame\Core\Filesystem::unlink(\osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getDoEditElementStorage($element.$this->getEditElementOption($element, 'temp_suffix')));
			}
		}

		$dir=str_replace('//', '/', osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getEditElementOption($element, 'file_dir').'/');
		$dir_tmp=str_replace('//', '/', osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getEditElementOption($element, 'file_dir').'/'.$this->getEditElementOption($element, 'file_dir_tmp').'/');

		$file_parts=pathinfo($_FILES[$element]['name']);

		if ($this->getEditElementOption($element, 'store_name')===true) {
			$this->setDoEditElementStorage($element.'_name', ($_FILES[$element]['name']));
			if ($this->getEditElementValue($element, 'name')!='') {
				$this->addDataElement($element.'_name', ['module'=>'hidden', 'name'=>$this->getEditElementValue($element, 'name').'_name']);
			}
		}

		if ($this->getEditElementOption($element, 'store_type')===true) {
			$this->setDoEditElementStorage($element.'_type', ($_FILES[$element]['type']));
			if ($this->getEditElementValue($element, 'name')!='') {
				$this->addDataElement($element.'_type', ['module'=>'hidden', 'name'=>$this->getEditElementValue($element, 'name').'_type']);
			}
		}

		if ($this->getEditElementOption($element, 'store_size')===true) {
			$this->setDoEditElementStorage($element.'_size', ($_FILES[$element]['size']));
			if ($this->getEditElementValue($element, 'name')!='') {
				$this->addDataElement($element.'_size', ['module'=>'hidden', 'name'=>$this->getEditElementValue($element, 'name').'_size']);
			}
		}

		if ($this->getEditElementOption($element, 'store_md5')===true) {
			$this->setDoEditElementStorage($element.'_md5', hash_file('md5', $_FILES[$element]['tmp_name']));
			if ($this->getEditElementValue($element, 'name')!='') {
				$this->addDataElement($element.'_md5', ['module'=>'hidden', 'name'=>$this->getEditElementValue($element, 'name').'_md5']);
			}
		}

		if ($this->getEditElementOption($element, 'store_sha1')===true) {
			$this->setDoEditElementStorage($element.'_sha1', hash_file('sha1', $_FILES[$element]['tmp_name']));
			if ($this->getEditElementValue($element, 'name')!='') {
				$this->addDataElement($element.'_sha1', ['module'=>'hidden', 'name'=>$this->getEditElementValue($element, 'name').'_sha1']);
			}
		}

		$file_name='';
		switch ($this->getEditElementOption($element, 'file_name')) {
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
				$dir=str_replace('//', '/', \osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getEditElementOption($element, 'file_dir').'/'.substr($file_name, 0, 2).'/'.substr($file_name, 2, 2).'/');
				break;
			case 'shared_sha1':
				$file_name=hash_file('sha1', $_FILES[$element]['tmp_name']).'.'.$file_parts['extension'];
				$dir=str_replace('//', '/', \osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getEditElementOption($element, 'file_dir').'/'.substr($file_name, 0, 2).'/'.substr($file_name, 2, 2).'/');
				break;
			default:
				$file_name=$this->getEditElementOption($element, 'file_name');
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

		$this->setDoEditElementStorage($element, str_replace(\osWFrame\Core\Settings::getStringVar('settings_abspath'), '', $file));
		$this->setDoEditElementStorage($element.$this->getEditElementOption($element, 'temp_suffix'), str_replace(\osWFrame\Core\Settings::getStringVar('settings_abspath'), '', $file_tmp));
	} elseif ((isset($_FILES[$element]))&&($_FILES[$element]['error']==4)) {
	} else {
		if ($this->getEditElementOption($element, 'read_only')!==true) {
			$this->setFilterErrorElementStorage($element.'_upload_error', true);
		}
	}

	if (\osWFrame\Core\Settings::catchValue($element.$this->getEditElementOption($element, 'delete_suffix'), '', 'p')==1) {
		if ($this->getDoEditElementStorage($element)!='') {
			\osWFrame\Core\Filesystem::unlink(\osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getDoEditElementStorage($element));
			$this->setDoEditElementStorage($element, '');
		}
		if ($this->getDoEditElementStorage($element.$this->getEditElementOption($element, 'temp_suffix'))!='') {
			\osWFrame\Core\Filesystem::unlink(\osWFrame\Core\Settings::getStringVar('settings_abspath').$this->getDoEditElementStorage($element.$this->getEditElementOption($element, 'temp_suffix')));
			$this->setDoEditElementStorage($element.$this->getEditElementOption($element, 'temp_suffix'), '');
		}
	}
}

?>