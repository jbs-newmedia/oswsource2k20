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

$osW_Template->addVoidTag('link', ['rel'=>'canonical', 'href'=>\osWFrame\Core\Navigation::getCanonicalUrl()]);
echo $osW_Template->getOutput('index', 'project');

?>