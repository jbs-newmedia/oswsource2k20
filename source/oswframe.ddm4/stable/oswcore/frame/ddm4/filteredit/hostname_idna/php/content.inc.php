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

use osWFrame\Core\Filter;

if ($this->getDoEditElementStorage($element) !== '') {
    if (Filter::verifyHostnameIDNAPattern($this->getDoEditElementStorage($element)) !== true) {
        $this->getTemplate()->Form()->addErrorMessage(
            $element,
            osWFrame\Core\StringFunctions::parseTextWithVars(
                $this->getGroupMessage('validation_element_incorrect'),
                $this->getFilterElementStorage($element)
            )
        );
        $this->setFilterErrorElementStorage($element, true);
    }
}
