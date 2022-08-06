<?php

/**
 * @var \osWFrame\Core\Template $osW_Template
 */

$foo='Hello World @ '.date('Y/m/d H/i/s');

$osW_Template->addStringTag('title', 'Hello World');

$osW_Template->setVarAsCopy('bar', $foo);

?>