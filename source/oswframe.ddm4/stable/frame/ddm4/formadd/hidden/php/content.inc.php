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

if (\osWFrame\Core\Settings::getAction()=='add') {
	$this->setAddElementStorage($element, $this->getAddElementOption($element, 'default_value'));
}

if (\osWFrame\Core\Settings::getAction()=='doadd') {
	$this->setDoAddElementStorage($element, osWFrame\Core\Settings::catchValue($element, '', 'p'));
}

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');

?>