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
$fields['element_title'] = $this->getEditElementValue($element, 'title');
$this->setFilterElementStorage($element, $fields);
$this->setFilterErrorElementStorage($element, false);

$file_name = $this->getDoEditElementStorage($element . $this->getEditElementOption($element, 'temp_suffix'));
$file_name_old = $this->getDoEditElementStorage($element);
$file_name_delete = (int)(Settings::catchValue(
    $element . $this->getEditElementOption($element, 'delete_suffix'),
    '',
    'p'
));
if ($this->getEditElementOption($element, 'required') === true) {
    if (((($file_name === '') || (filesize(
        Settings::getStringVar('settings_abspath') . $file_name
    ) === 0)) && ($file_name_old === '')) || ($file_name_delete === 1)
    ) {
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
            $this->getGroupMessage('validation_image_uploaderror'),
            $this->getFilterElementStorage($element)
        )
    );
    $this->setFilterErrorElementStorage($element, true);
    $this->setFilterErrorElementStorage($element . '_upload_error', false);
}

if (($this->getFilterErrorElementStorage($element) !== true) && ($file_name !== '')) {
    $getimagesize = getimagesize(Settings::getStringVar('settings_abspath') . $file_name);

    if ($getimagesize !== false) {
        if (($this->getFilterErrorElementStorage($element) !== true) && (is_array(
            $this->getEditElementValidation($element, 'types')
        )) && (count($this->getEditElementValidation($element, 'types')) > 0)
        ) {
            $finfo = finfo_open(\FILEINFO_MIME_TYPE);
            if (!in_array(
                finfo_file($finfo, osWFrame\Core\Settings::getStringVar('settings_abspath') . $file_name),
                $this->getEditElementValidation($element, 'types'),
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
            $this->getEditElementValidation($element, 'extensions')
        )) && (count($this->getEditElementValidation($element, 'extensions')) > 0)
        ) {
            if (!in_array(
                strtolower(pathinfo(Settings::getStringVar('settings_abspath') . $file_name, \PATHINFO_EXTENSION)),
                $this->getEditElementValidation($element, 'extensions'),
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

        if (($this->getFilterErrorElementStorage($element) !== true) && ($this->getEditElementValidation(
            $element,
            'size_min'
        ) !== '')
        ) {
            if (filesize(Settings::getStringVar('settings_abspath') . $file_name) < $this->getEditElementValidation(
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

        if (($this->getFilterErrorElementStorage($element) !== true) && ($this->getEditElementValidation(
            $element,
            'size_max'
        ) !== '')
        ) {
            if (filesize(Settings::getStringVar('settings_abspath') . $file_name) > $this->getEditElementValidation(
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

        if (($this->getFilterErrorElementStorage($element) !== true) && ($this->getEditElementValidation(
            $element,
            'width_min'
        ) !== '')
        ) {
            if ($getimagesize[0] < $this->getEditElementValidation($element, 'width_min')) {
                $this->getTemplate()->Form()->addErrorMessage(
                    $element,
                    osWFrame\Core\StringFunctions::parseTextWithVars(
                        $this->getGroupMessage('validation_imagewidth_tosmall'),
                        $this->getFilterElementStorage($element)
                    )
                );
                $this->setFilterErrorElementStorage($element, true);
            }
        }

        if (($this->getFilterErrorElementStorage($element) !== true) && ($this->getEditElementValidation(
            $element,
            'width_max'
        ) !== '')
        ) {
            if ($getimagesize[0] > $this->getEditElementValidation($element, 'width_max')) {
                $this->getTemplate()->Form()->addErrorMessage(
                    $element,
                    osWFrame\Core\StringFunctions::parseTextWithVars(
                        $this->getGroupMessage('validation_imagewidth_tobig'),
                        $this->getFilterElementStorage($element)
                    )
                );
                $this->setFilterErrorElementStorage($element, true);
            }
        }

        if (($this->getFilterErrorElementStorage($element) !== true) && ($this->getEditElementValidation(
            $element,
            'height_min'
        ) !== '')
        ) {
            if ($getimagesize[1] < $this->getEditElementValidation($element, 'height_min')) {
                $this->getTemplate()->Form()->addErrorMessage(
                    $element,
                    osWFrame\Core\StringFunctions::parseTextWithVars(
                        $this->getGroupMessage('validation_imageheight_tosmall'),
                        $this->getFilterElementStorage($element)
                    )
                );
                $this->setFilterErrorElementStorage($element, true);
            }
        }

        if (($this->getFilterErrorElementStorage($element) !== true) && ($this->getEditElementValidation(
            $element,
            'height_max'
        ) !== '')
        ) {
            if ($getimagesize[1] > $this->getEditElementValidation($element, 'height_max')) {
                $this->getTemplate()->Form()->addErrorMessage(
                    $element,
                    osWFrame\Core\StringFunctions::parseTextWithVars(
                        $this->getGroupMessage('validation_imageheight_tobig'),
                        $this->getFilterElementStorage($element)
                    )
                );
                $this->setFilterErrorElementStorage($element, true);
            }
        }

        if (($this->getFilterErrorElementStorage($element) !== true) && (is_array(
            $this->getEditElementValidation($element, 'filter')
        ))
        ) {
            foreach ($this->getEditElementValidation($element, 'filter') as $filter => $values) {
                if ($this->getFilterErrorElementStorage($element) !== true) {
                    $values['module'] = $filter;
                    $this->parseFilterEditElementPHP($element, $values);
                }
            }
        }
    } else {
        $this->getTemplate()->Form()->addErrorMessage(
            $element,
            osWFrame\Core\StringFunctions::parseTextWithVars(
                $this->getGroupMessage('validation_image_fileerror'),
                $this->getFilterElementStorage($element)
            )
        );
        $this->setFilterErrorElementStorage($element, true);
    }
}
