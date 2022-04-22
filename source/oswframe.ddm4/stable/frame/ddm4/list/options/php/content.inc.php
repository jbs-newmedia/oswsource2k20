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

$view=false;

if (($this->getCounter('edit_elements')>0)&&($this->getListElementOption($element, 'disable_edit')!==true)) {
	$view=true;
}

if (($this->getCounter('delete_elements')>0)&&($this->getListElementOption($element, 'disable_delete')!==true)) {
	$view=true;
}

if (($this->getGroupOption('enable_log')===true)&&($this->getListElementOption($element, 'disable_log')!==true)) {
	$view=true;
}

if (is_array($this->getListElementOption($element, 'links'))) {
	$view=true;
}

if ($view===true) {
	$_columns[$element]=['name'=>$element, 'order'=>false, 'search'=>false,];

	$this->incCounter('list_view_elements');
}

?>