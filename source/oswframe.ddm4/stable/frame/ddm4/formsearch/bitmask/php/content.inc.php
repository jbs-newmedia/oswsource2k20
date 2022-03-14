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

if (\osWFrame\Core\Settings::getAction()=='dosearch') {
	$bitmask='';
	$set=false;
	$data=$this->getSearchElementOption($element, 'data');
	ksort($data);
	$i=0;
	foreach ($data as $key=>$value) {
		if ($i<$key) {
			while ($i<$key) {
				$bitmask.='_';
				$i++;
			}
		}
		$_value=\osWFrame\Core\Settings::catchValue($element.'_'.$key, 0, 'p');
		if ($_value==1) {
			$bitmask.='1';
			$set=true;
		} else {
			$bitmask.='_';
		}
		$i++;
	}

	if ($set==true) {
		$this->setSearchElementStorage($element, $bitmask, 'string');
	} else {
		$this->setSearchElementStorage($element, '', 'string');
	}
}

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');

?>