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

if (($view_data[$this->getListElementValue($element, 'name')]=='')||($view_data[$this->getListElementValue($element, 'name')]=='00000000')) {
	$view_data[$this->getListElementValue($element, 'name')]='---';
} else {
	if ($this->getListElementOption($element, 'month_asname')===true) {
		$view_data[$this->getListElementValue($element, 'name')]=strftime(str_replace('%m.', ' %B ', $this->getListElementOption($element, 'format')), mktime(12, 0, 0, substr($view_data[$this->getListElementValue($element, 'name')], 4, 2), substr($view_data[$this->getListElementValue($element, 'name')], 6, 2), substr($view_data[$this->getListElementValue($element, 'name')], 0, 4)));
	} else {
		$view_data[$this->getListElementValue($element, 'name')]=strftime($this->getListElementOption($element, 'format'), mktime(12, 0, 0, substr($view_data[$this->getListElementValue($element, 'name')], 4, 2), substr($view_data[$this->getListElementValue($element, 'name')], 6, 2), substr($view_data[$this->getListElementValue($element, 'name')], 0, 4)));
	}
}

?>