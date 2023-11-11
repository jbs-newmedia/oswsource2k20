<?php declare(strict_types=0);

/**
 * This file is part of the HelloWorld package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   HelloWorld
 * @link      https://oswframe.com
 * @license   MIT License
 *
 * @var \osWFrame\Core\Template $osW_Template
 */

$foo = 'Hello World @ ' . date('Y/m/d H/i/s');

$osW_Template->addStringTag('title', 'Hello World');

$osW_Template->setVarAsCopy('bar', $foo);
