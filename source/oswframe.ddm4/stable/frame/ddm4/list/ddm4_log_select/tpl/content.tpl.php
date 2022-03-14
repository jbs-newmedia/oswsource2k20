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
if (isset($data[$view_data[$this->getListElementValue($element, 'name')]])) {
	$view_data['__ddm4__key__']=$view_data[$this->getListElementValue($element, 'name')];
	$view_data[$this->getListElementValue($element, 'name')]=\osWFrame\Core\HTML::outputString($data[$view_data[$this->getListElementValue($element, 'name')]]);
}

?>