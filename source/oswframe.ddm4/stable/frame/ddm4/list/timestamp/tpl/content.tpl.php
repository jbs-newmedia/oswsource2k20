<?php

/*
 * Author: Juergen Schwind
 * Copyright: 2011 Juergen Schwind
 * Link: https://oswframe.com
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details:
 * http://www.gnu.org/licenses/gpl.html
 *
 */

if ($this->getListElementOption($element, 'month_asname')===true) {
	$view_data[$this->getListElementValue($element, 'name')]=strftime(str_replace('%m.', ' %B ', $this->getListElementOption($element, 'date_format')), $view_data[$this->getListElementValue($element, 'name')]).' '.\osWFrame\Core\HTML::outputString($this->getGroupMessage('text_clock'));
} else {
	$view_data[$this->getListElementValue($element, 'name')]=strftime($this->getListElementOption($element, 'date_format'), $view_data[$this->getListElementValue($element, 'name')]).' '.\osWFrame\Core\HTML::outputString($this->getGroupMessage('text_clock'));
}

?>