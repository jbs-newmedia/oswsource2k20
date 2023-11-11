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
 *
 */

use osWFrame\Core\Navigation;
use osWFrame\Core\Template;

$osW_Template->addVoidTag('link', [
    'rel' => 'canonical',
    'href' => Navigation::getCanonicalUrl(),
]);
echo $osW_Template->getOutput('index', 'default');
