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

$fields=[];
$fields['element']=$element;
$fields['element_title']=$this->getSendElementValue($element, 'title');
$this->setFilterElementStorage($element, $fields);
$this->setFilterErrorElementStorage($element, false);

if (($this->getFilterErrorElementStorage($element)!==true)&&(is_array($this->getSendElementValidation($element, 'filter')))) {
	foreach ($this->getSendElementValidation($element, 'filter') as $filter=>$values) {
		if (($this->getFilterErrorElementStorage($element)!==true)) {
			$values['module']=$filter;
			if ((!isset($values['enabled']))||($values['enabled']===true)) {
				$this->parseFilterSendElementPHP($element, $values);
			}
		}
	}
}

?>