<?php declare(strict_types=0);

/**
 * This file is part of the osWFrame package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   osWFrame
 * @link      https://oswframe.com
 * @license   MIT License
 *
 * @var string $element
 * @var \osWFrame\Core\DDM4 $this
 *
 */

use osWFrame\Core\Settings;

if (Settings::getAction() === 'dosearch') {
    if (Settings::catchValue($element, '', 'p') !== '%') {
        $this->setSearchElementStorage($element, osWFrame\Core\Settings::catchValue($element, '', 'p'));
    } else {
        $this->setSearchElementStorage($element, '');
    }
}

$this->incCounter('form_elements');
$this->incCounter('form_elements_required');
