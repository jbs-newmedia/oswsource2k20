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

if (\osWFrame\Core\Settings::getAction()=='doedit') {
	$this->setDoEditElementStorage($element, osWFrame\Core\Settings::catchValue($element, '', 'p'));
}

$file=$this->getEditElementOption($element, 'file');
if ($file=='') {
	$file=[];
}
$internallink=$this->getEditElementOption($element, 'internallink');
if ($internallink=='') {
	$internallink=[];
}
$options=$this->getEditElementOption($element, 'ckeditor5');
if ($options=='') {
	$options=[];
}

$default_conf=\osWFrame\Core\CKEditor5::getDefaultConf();
if (!isset($options['conf'])) {
	$options['conf']=$default_conf;
} else {
	if (isset($options['conf']['toolbar']))	{
		unset($default_conf['toolbar']);
	}
	$options['conf']=array_merge_recursive($default_conf, $options['conf']);
}
if (!isset($options['then'])) {
	$options['then']=[];
}
if (!isset($options['catch'])) {
	$options['catch']=[];
}
if (!isset($options['file'])) {
	$options['file']=[];
}
if (!isset($options['conf']['language'])) {
	$options['conf']['language']=\osWFrame\Core\Language::getCurrentLanguageShort();
}

if (($internallink!=[])&&(isset($internallink['loader']))) {
	$options['conf']['internallink']=[];
	$options['conf']['internallink']['testmode']=false;
	$options['conf']['internallink']['autocompleteurl']=$this->getTemplate()->buildhrefLink('scripts', 'script=_ckeditor5_internallink&action=autocompleteurl&loader='.$internallink['loader'].'&s={searchTerm}', false);
	$options['conf']['internallink']['titleurl']=$this->getTemplate()->buildhrefLink('scripts', 'script=_ckeditor5_internallink&action=titleurl&loader='.$internallink['loader'].'&s={internalLinkId}', false);
	$options['conf']['internallink']['previewurl']=$this->getTemplate()->buildhrefLink('scripts', 'script=_ckeditor5_internallink&action=previewurl&loader='.$internallink['loader'].'&s={internalLinkId}', false);
}

$this->getTemplate()->addCSSCodeHead('
.ddm_element_'.$element.' .ck.ck-toolbar {
	border-top-left-radius: var(--bs-border-radius) !important;
	border-top-right-radius: var(--bs-border-radius) !important;
}

.ddm_element_'.$element.' .ck.ck-content {
	border-bottom-left-radius: var(--bs-border-radius) !important;
	border-bottom-right-radius: var(--bs-border-radius) !important;
	min-height: 200px;
}
');

$CKEditor5=new \osWFrame\Core\CKEditor5('#', $element, $options['conf'], $options['then'], $options['catch']);

foreach ($file as $key=>$value) {
	switch ($key) {
		case 'file_dir':
			$CKEditor5->setFileDir($value);
			break;
		case 'file_name':
			$CKEditor5->setFileName($value);
			break;
		case 'file_types':
			$CKEditor5->setFileTypes($value);
			break;
		case 'file_extensions':
			$CKEditor5->setFileExtensions($value);
			break;
		case 'file_size_min':
			$CKEditor5->setFileSizeMin($value);
			break;
		case 'file_size_max':
			$CKEditor5->setFileSizeMax($value);
			break;
		case 'file_width_min':
			$CKEditor5->setFileWidthMin($value);
			break;
		case 'file_width_max':
			$CKEditor5->setFileWidthMax($value);
			break;
		case 'file_height_min':
			$CKEditor5->setFileHeightMin($value);
			break;
		case 'file_height_max':
			$CKEditor5->setFileHeightMax($value);
			break;
		default:
			if (is_string($value)) {
				$CKEditor5->setFileStringValue($key, $value);
			}
			if (is_int($value)) {
				$CKEditor5->setFileIntValue($key, $value);
			}
	}
}

if ((!isset($options['upload']))||(!isset($options['upload']['url']))) {
	$CKEditor5->setSimpleUploadUrl($this->getTemplate()->buildhrefLink('scripts', 'script=_ckeditor5_simple_upload&session_enabled=1&var='.md5('_ckeditor5_simple_upload#'.$element.'#'.\osWFrame\Core\Settings::getStringVar('settings_protection_salt')), false));
} else {
	$CKEditor5->setSimpleUploadUrl($options['upload']['url']);
}

$this->getTemplate()->addJSCodeHead('
$(function () {
	'.$CKEditor5->getJS().'
});
');

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');

?>