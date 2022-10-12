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
	$this->setSearchElementStorage($element, osWFrame\Core\Settings::catchValue($element, '', 'p'));
}

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');

?>