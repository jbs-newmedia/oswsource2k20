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

$__link=$this->getListElementOption($element, 'link');

$_link='<a'.((isset($__link['target']))?' target="'.$__link['target'].'"':'').' href="'.$this->getTemplate()->buildhrefLink(((isset($__link['module']))?$__link['module']:$this->getDirectModule()), $this->getGroupOption('index', 'database').'='.$this->getListStorageValue($this->getGroupOption('index', 'database')).((isset($__link['parameter']))?'&'.$__link['parameter']:'')).'">'.(\osWFrame\Core\HTML::outputString($this->getListStorageValue($this->getListElementValue($element, 'name')))).'</a>';

$view_data[$this->getListElementValue($element, 'name')]=$_link;

?>