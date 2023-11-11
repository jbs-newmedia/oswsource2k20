<?php declare(strict_types=0);

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license MIT License
 *
 * @var \osWFrame\Core\Template $osW_Template
 */

$osW_Template->addVoidTag('link', ['rel' => 'canonical', 'href' => \osWFrame\Core\Navigation::getCanonicalUrl()]);
echo $osW_Template->getOutput('index', 'project');
