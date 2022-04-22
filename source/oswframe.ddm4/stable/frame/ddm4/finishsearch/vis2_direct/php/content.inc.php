<?php

/**
 * This file is part of the VIS2 package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2
 * @link https://oswframe.com
 * @license MIT License
 */

$_messages=[];
if (\osWFrame\Core\MessageStack::getMessageCount('ddm4_'.$this->getName())>0) {
	foreach (\osWFrame\Core\MessageStack::getMessages('ddm4_'.$this->getName()) as $type=>$messages) {
		foreach ($messages as $message) {
			$_messages[]='window.parent.vis2_notify("'.$message['msg'].'", "'.$type.'");';
		}
	}
	osWFrame\Core\MessageStack::clearMessages('ddm4_'.$this->getName());
}

$this->getTemplate()->addJSCodeHead('
$(function() {
'.implode("\n", $_messages).'
});
');

?>