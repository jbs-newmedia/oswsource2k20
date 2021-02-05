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

$data=$this->getListElementOption($element, 'data');
if (isset($data[$view_data[$this->getListElementValue($element, 'name')]])) {
	$view_data['__ddm4__key__']=$view_data[$this->getListElementValue($element, 'name')];
	$view_data[$this->getListElementValue($element, 'name')]=\osWFrame\Core\HTML::outputString($data[$view_data[$this->getListElementValue($element, 'name')]]);
}

?>