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

if ((isset($options['name']))&&($options['name']!='')) {
	$_columns[$options['name']]=['name'=>$options['name'], 'order'=>true, 'search'=>false, 'hidden'=>true];
}

$this->incCounter('list_view_elements');

?>