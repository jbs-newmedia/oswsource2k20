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

if (\osWFrame\Core\Settings::getAction()=='doadd') {
	$bitmask='';
	$data=$this->getAddElementOption($element, 'data');
	ksort($data);
	$i=0;
	foreach ($data as $key=>$value) {
		if ($i<$key) {
			while ($i<$key) {
				$bitmask.='0';
				$i++;
			}
		}
		$_value=\osWFrame\Core\Settings::catchValue($element.'_'.$key, 0, 'p');
		if ($_value==1) {
			$bitmask.='1';
		} else {
			$bitmask.='0';
		}
		$i++;
	}
	$this->setDoAddElementStorage($element, $bitmask, 'string');
}

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');

?>