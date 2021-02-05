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

if ($view_data[$this->getListElementValue($element, 'name')]!='') {
	$view_data[$this->getListElementValue($element, 'name')]='<a target="_blank" href="'.$view_data[$this->getListElementValue($element, 'name')].'">'.\osWFrame\Core\HTML::outputString($this->getGroupMessage('text_image_view')).'</a>';
}

?>