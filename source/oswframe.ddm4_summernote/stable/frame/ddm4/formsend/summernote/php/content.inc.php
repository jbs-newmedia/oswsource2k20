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

if (\osWFrame\Core\Settings::getAction()=='dosend') {
	$this->setDoSendElementStorage($element, osWFrame\Core\Settings::catchValue($element, '', 'p'));
}

$options=$this->getSendElementOption($element, 'summernote');
if ($options=='') {
	$options=[];
}
if (!isset($options['lang'])) {
	$options['lang']=str_replace('_', '-', \osWFrame\Core\Language::getCurrentLanguage());
}


$this->getTemplate()->addJSCodeHead('
$(function () {
	$(\'#'.$element.'\').summernote({
		callbacks: {
			onPaste: function (e) {
				var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData(\'Text\');
				e.preventDefault();
				document.execCommand(\'insertText\', false, bufferText);
				bufferText=$(\'#'.$element.'\').summernote("code");
				bufferText = bufferText.replace(/(\<\/\p>\<p\>)/g, \'<br/>\');
				$(\'#'.$element.'\').summernote("code", bufferText)
			}
		},
'.substr(json_encode($options), 1, -1).'
	});
});
');

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');

?>