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

if (\osWFrame\Core\Settings::getAction()=='doedit') {
	$multicheckbox=[];
	$data=$this->getEditElementOption($element, 'data');
	ksort($data);
	foreach ($data as $key=>$value) {
		$_value=\osWFrame\Core\Settings::catchValue($element.'_'.$key, 0, 'p');
		if ($_value==1) {
			$multicheckbox[]=$key;
		}
	}
	$this->setDoEditElementStorage($element, implode($this->getEditElementOption($element, 'separator'), $multicheckbox), 'string');
}

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');

?>