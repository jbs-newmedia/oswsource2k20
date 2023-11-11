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

if ($this->getDoAddElementStorage($element . $this->getAddElementOption($element, 'temp_suffix')) !== '') {
    rename(
        $this->getDoAddElementStorage($element . $this->getAddElementOption($element, 'temp_suffix')),
        $this->getDoAddElementStorage($element)
    );
}
