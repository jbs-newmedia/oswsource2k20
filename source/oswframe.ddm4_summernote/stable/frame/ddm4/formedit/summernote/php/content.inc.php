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

$options=$this->getEditElementOption($element, 'summernote');
if ($options=='') {
	$options=[];
}
if (!isset($options['lang'])) {
	$options['lang']=str_replace('_', '-', \osWFrame\Core\Language::getCurrentLanguage());
}

$this->getTemplate()->addJSCodeHead('
$(function () {
	$(\'#'.$element.'\').summernote({
'.substr(json_encode($options), 1, -1).'
	});
});
');

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');

?>