<?php declare(strict_types=0);

/**
 * This file is part of the osWFrame package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   osWFrame
 * @link      https://oswframe.com
 * @license   MIT License
 *
 * @var string $element
 * @var \osWFrame\Core\DDM4 $this
 *
 */

use osWFrame\Core\Language;
use osWFrame\Core\Settings;

if (Settings::getAction() === 'doedit') {
    $this->setDoEditElementStorage($element, osWFrame\Core\Settings::catchValue($element, '', 'p'));
}

$options = $this->getEditElementOption($element, 'summernote');
if ($options === '') {
    $options = [];
}
if (!isset($options['lang'])) {
    $options['lang'] = str_replace('_', '-', Language::getCurrentLanguage());
}

$this->getTemplate()->addJSCodeHead(
    '
$(function () {
	$(\'#' . $element . '\').summernote({
		callbacks: {
			onPaste: function (e) {
				var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData(\'Text\');
				e.preventDefault();
				document.execCommand(\'insertText\', false, bufferText);
				bufferText=$(\'#' . $element . '\').summernote("code");
				bufferText = bufferText.replace(/(\<\/\p>\<p\>)/g, \'<br/>\');
				$(\'#' . $element . '\').summernote("code", bufferText)
			}
		},
' . substr(json_encode($options), 1, -1) . '
	});
});
'
);

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');
