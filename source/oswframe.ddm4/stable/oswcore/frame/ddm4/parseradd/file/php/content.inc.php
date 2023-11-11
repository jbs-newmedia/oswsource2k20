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

$fields = [];
$fields['element'] = $element;
$fields['element_title'] = $this->getAddElementValue($element, 'title');
$this->setFilterElementStorage($element, $fields);
$this->setFilterErrorElementStorage($element, false);

$file_name = $this->getDoAddElementStorage($element . $this->getAddElementOption($element, 'temp_suffix'));

if ($this->getAddElementOption($element, 'required') === true) {
    if (($file_name === '') || (filesize(Settings::getStringVar('settings_abspath') . $file_name) === 0)) {
        $this->getTemplate()->Form()->addErrorMessage(
            $element,
            osWFrame\Core\StringFunctions::parseTextWithVars(
                $this->getGroupMessage('validation_image_miss'),
                $this->getFilterElementStorage($element)
            )
        );
        $this->setFilterErrorElementStorage($element, true);
    }
}

if (($this->getFilterErrorElementStorage($element) !== true) && ($this->getFilterErrorElementStorage(
    $element . '_upload_error'
) === true)
) {
    $this->getTemplate()->Form()->addErrorMessage(
        $element,
        osWFrame\Core\StringFunctions::parseTextWithVars(
            $this->getGroupMessage('validation_file_uploaderror'),
            $this->getFilterElementStorage($element)
        )
    );
    $this->setFilterErrorElementStorage($element, true);
    $this->setFilterErrorElementStorage($element . '_upload_error', false);
}

if (($this->getFilterErrorElementStorage($element) !== true) && ($file_name !== '')) {
    if (($this->getFilterErrorElementStorage($element) !== true) && (is_array(
        $this->getAddElementValidation($element, 'types')
    )) && (count($this->getAddElementValidation($element, 'types')) > 0)
    ) {
        $finfo = finfo_open(\FILEINFO_MIME_TYPE);
        if (!in_array(
            finfo_file($finfo, osWFrame\Core\Settings::getStringVar('settings_abspath') . $file_name),
            $this->getAddElementValidation($element, 'types'),
            true
        )
        ) {
            $this->getTemplate()->Form()->addErrorMessage(
                $element,
                osWFrame\Core\StringFunctions::parseTextWithVars(
                    $this->getGroupMessage('validation_file_typeerror'),
                    $this->getFilterElementStorage($element)
                )
            );
            $this->setFilterErrorElementStorage($element, true);
        }
    }

    if (($this->getFilterErrorElementStorage($element) !== true) && (is_array(
        $this->getAddElementValidation($element, 'extensions')
    )) && (count($this->getAddElementValidation($element, 'extensions')) > 0)
    ) {
        if (!in_array(
            strtolower(pathinfo(Settings::getStringVar('settings_abspath') . $file_name, \PATHINFO_EXTENSION)),
            $this->getAddElementValidation($element, 'extensions'),
            true
        )
        ) {
            $this->getTemplate()->Form()->addErrorMessage(
                $element,
                osWFrame\Core\StringFunctions::parseTextWithVars(
                    $this->getGroupMessage('validation_file_extensionerror'),
                    $this->getFilterElementStorage($element)
                )
            );
            $this->setFilterErrorElementStorage($element, true);
        }
    }

    if (($this->getFilterErrorElementStorage($element) !== true) && ($this->getAddElementValidation(
        $element,
        'size_min'
    ) !== '')
    ) {
        if (filesize(Settings::getStringVar('settings_abspath') . $file_name) < $this->getAddElementValidation(
            $element,
            'size_min'
        )
        ) {
            $this->getTemplate()->Form()->addErrorMessage(
                $element,
                osWFrame\Core\StringFunctions::parseTextWithVars(
                    $this->getGroupMessage('validation_file_tosmall'),
                    $this->getFilterElementStorage($element)
                )
            );
            $this->setFilterErrorElementStorage($element, true);
        }
    }

    if (($this->getFilterErrorElementStorage($element) !== true) && ($this->getAddElementValidation(
        $element,
        'size_max'
    ) !== '')
    ) {
        if (filesize(Settings::getStringVar('settings_abspath') . $file_name) > $this->getAddElementValidation(
            $element,
            'size_max'
        )
        ) {
            $this->getTemplate()->Form()->addErrorMessage(
                $element,
                osWFrame\Core\StringFunctions::parseTextWithVars(
                    $this->getGroupMessage('validation_file_tobig'),
                    $this->getFilterElementStorage($element)
                )
            );
            $this->setFilterErrorElementStorage($element, true);
        }
    }

    if (($this->getFilterErrorElementStorage($element) !== true) && (is_array(
        $this->getAddElementValidation($element, 'filter')
    ))
    ) {
        foreach ($this->getAddElementValidation($element, 'filter') as $filter => $values) {
            if ($this->getFilterErrorElementStorage($element) !== true) {
                $values['module'] = $filter;
                $this->parseFilterAddElementPHP($element, $values);
            }
        }
    }
}
