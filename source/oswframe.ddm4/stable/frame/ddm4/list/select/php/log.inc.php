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

$data=$this->getEditElementOption($element_name, 'data');

if (isset($data[$value_old])) {
	$value_old=\osWFrame\Core\HTML::outputString($data[$value_old]);
}

if (isset($data[$value_new])) {
	$value_new=\osWFrame\Core\HTML::outputString($data[$value_new]);
}

?>