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

if ($this->getListElementOption($element, 'show_output')===true) {
	$view_data[$this->getListElementValue($element, 'name')]=$view_data[$this->getListElementValue($element, 'name')];
} else {
	$count=strlen($view_data[$this->getListElementValue($element, 'name')]);
	if ($count==1) {
		$t=strlen($view_data[$this->getListElementValue($element, 'name')]).' '.$this->getListElementOption($element, 'text_char');
	} else {
		$t=strlen($view_data[$this->getListElementValue($element, 'name')]).' '.$this->getListElementOption($element, 'text_chars');
	}

	if ($this->getListElementOption($element, 'show_dialog')===true) {
		$view_data[$this->getListElementValue($element, 'name')]='<a style="cursor:pointer;" onclick="openDDM4Dialog_'.$ddm_group.'(this);" pageTitle="'.$this->getListElementValue($element, 'title').'" pageName="'.$view_data[$this->getListElementValue($element, 'name')].'">'.$t.'</a>';
	} else {
		$view_data[$this->getListElementValue($element, 'name')]=$t;
	}
}

?>