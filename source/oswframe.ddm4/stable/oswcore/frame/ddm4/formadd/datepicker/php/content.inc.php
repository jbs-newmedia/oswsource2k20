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

if (Settings::getAction() === 'doadd') {
    $date_new = '';
    $data_old = Settings::catchValue($element, '', 'p');
    $format = $this->getAddElementOption($element, 'format');
    $pos = strpos($format, '%Y');
    if ($pos !== false) {
        $date_new .= substr($data_old, $pos, 4);
    }
    $pos = strpos($format, '%y');
    if ($pos !== false) {
        $date_new .= substr($data_old, $pos, 2);
    }
    $pos = strpos($format, '%m');
    if ($pos !== false) {
        $date_new .= substr($data_old, $pos, 2);
    }
    $pos = strpos($format, '%d');
    if ($pos !== false) {
        $date_new .= substr($data_old, $pos, 2);
    }
    $this->setDoAddElementStorage($element, $date_new);
}

$format = $this->getAddElementOption($element, 'format');
$format = str_replace('%Y', 'yyyy', $format);
$format = str_replace('%y', 'yy', $format);
$format = str_replace('%d', 'dd', $format);
$format = str_replace('%m', 'mm', $format);

$this->getTemplate()->addJSCodeHead(
    '
	$(function(){
		$("#' . $element . '").datepicker({
			orientation: "' . $this->getAddElementOption($element, 'orientation') . '",
			format: "' . $format . '",
			language: "' . Language::getCurrentLanguage('short') . '",
			weekStart: "' . $this->getAddElementOption($element, 'weekStart') . '",
			startDate: "' . $this->getAddElementOption($element, 'startDate') . '",
			endDate: "' . $this->getAddElementOption($element, 'endDate') . '"
		});
	});
'
);

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');
