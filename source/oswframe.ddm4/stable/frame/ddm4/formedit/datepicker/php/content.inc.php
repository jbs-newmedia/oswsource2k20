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

if (\osWFrame\Core\Settings::getAction()=='doedit') {
	$date_new='';
	$data_old=\osWFrame\Core\Settings::catchValue($element, '', 'p');
	$format=$this->getEditElementOption($element, 'format');
	$pos=strpos($format, '%Y');
	if ($pos!==false) {
		$date_new.=substr($data_old, $pos, 4);
	}
	$pos=strpos($format, '%y');
	if ($pos!==false) {
		$date_new.=substr($data_old, $pos, 2);
	}
	$pos=strpos($format, '%m');
	if ($pos!==false) {
		$date_new.=substr($data_old, $pos, 2);
	}
	$pos=strpos($format, '%d');
	if ($pos!==false) {
		$date_new.=substr($data_old, $pos, 2);
	}
	$this->setDoEditElementStorage($element, $date_new);
}

$format=$this->getEditElementOption($element, 'format');
$format=str_replace('%Y', 'yyyy', $format);
$format=str_replace('%y', 'yy', $format);
$format=str_replace('%d', 'dd', $format);
$format=str_replace('%m', 'mm', $format);

$this->getTemplate()->addJSCodeHead('
	$(function(){
		$("#'.$element.'").datepicker({
			orientation: "'.$this->getEditElementOption($element, 'orientation').'",
			format: "'.$format.'",
			language: "'.\osWFrame\Core\Language::getCurrentLanguage('short').'",
			weekStart: "'.$this->getEditElementOption($element, 'weekStart').'",
			startDate: "'.$this->getEditElementOption($element, 'startDate').'",
			endDate: "'.$this->getEditElementOption($element, 'endDate').'"
		});
	});
');

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');

?>