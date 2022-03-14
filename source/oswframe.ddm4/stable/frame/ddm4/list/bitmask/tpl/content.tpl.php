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

$data=$this->getListElementOption($element, 'data');
$result=[];
foreach ($data as $key=>$value) {
	if ((isset($view_data[$this->getListElementValue($element, 'name')][$key]))&&($view_data[$this->getListElementValue($element, 'name')][$key]=='1')) {
		$result[]=$value;
	}
}

$view_data[$this->getListElementValue($element, 'name')]=implode($this->getListElementOption($element, 'separator'), $result);

?>