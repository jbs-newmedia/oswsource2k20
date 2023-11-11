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

use osWFrame\Core\HTML;

?>

<div class="form-group ddm_element_<?php echo $this->getSendElementValue($element, 'id') ?>">

    <?php /* label */ ?>
    <label class="form-label"
           for="<?php echo $element ?>"><?php echo HTML::outputString(
               $this->getSendElementValue($element, 'title')
           ) ?><?php if ($this->getSendElementOption($element, 'required') === true): ?><?php echo $this->getGroupMessage(
               'form_title_required_icon'
           ) ?><?php endif ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>

    <?php if ($this->getSendElementOption($element, 'read_only') === true): ?>

        <?php /* read only */ ?>

        <?php if ($this->getSendElementOption($element, 'blank_value') === true): ?>

            <?php $data = [
                           '' => '',
                       ] + $this->getSendElementOption($element, 'data'); ?>

        <?php else: ?>

            <?php $data = $this->getSendElementOption($element, 'data'); ?>

        <?php endif ?>

        <?php if ((isset($data[$this->getSendElementStorage($element)])) && ($data[$this->getSendElementStorage(
            $element
        )] !== '')
        ): ?>

            <div
                class="form-control readonly"><?php echo HTML::outputString(
                    $data[$this->getSendElementStorage($element)]
                ) ?></div>

        <?php else: ?>

            <div class="form-control readonly">&nbsp;</div>

        <?php endif ?>

        <?php echo $this->getTemplate()->Form()->drawHiddenField(
            $element,
            $this->getSendElementStorage($element),
            []
        ) ?>

    <?php else: ?>

        <?php /* input */ ?>

        <?php if ($this->getSendElementOption($element, 'blank_value') === true): ?>

            <?php if ($this->getSendElementValidation($element, 'module') === 'integer'): ?>

                <?php echo $this->getTemplate()->Form()->drawSelectField(
                    $element,
                    [
                        0 => ' ',
                    ] + $this->getSendElementOption($element, 'data'),
                    $this->getSendElementStorage($element),
                    [
                        'input_class' => 'selectpicker select-ellipsis-fix form-control',
                        'input_errorclass' => 'is-invalid',
                        'input_parameter' => ' data-style="custom-select" data-size="' . $this->getSendElementOption(
                            $element,
                            'data_size'
                        ) . '" data-live-search="' . $this->getSendElementOption(
                            $element,
                            'live_search'
                        ) . '" title="' . $this->getSendElementOption($element, 'data_choose') . '"',
                    ]
                ) ?>

            <?php else: ?>

                <?php echo $this->getTemplate()->Form()->drawSelectField(
                    $element,
                    [
                        '' => ' ',
                    ] + $this->getSendElementOption($element, 'data'),
                    $this->getSendElementStorage($element),
                    [
                        'input_class' => 'selectpicker select-ellipsis-fix form-control',
                        'input_errorclass' => 'is-invalid',
                        'input_parameter' => ' data-style="custom-select" data-size="' . $this->getSendElementOption(
                            $element,
                            'data_size'
                        ) . '" data-live-search="' . $this->getSendElementOption(
                            $element,
                            'live_search'
                        ) . '" title="' . $this->getSendElementOption($element, 'data_choose') . '"',
                    ]
                ) ?>

            <?php endif ?>

        <?php else: ?>

            <?php echo $this->getTemplate()->Form()->drawSelectField(
                $element,
                $this->getSendElementOption($element, 'data'),
                $this->getSendElementStorage($element),
                [
                    'input_class' => 'selectpicker form-control',
                    'input_errorclass' => 'is-invalid',
                    'input_parameter' => ' data-style="custom-select" data-size="' . $this->getSendElementOption(
                        $element,
                        'data_size'
                    ) . '" data-live-search="' . $this->getSendElementOption(
                        $element,
                        'live_search'
                    ) . '" title="' . $this->getSendElementOption($element, 'data_choose') . '"',
                ]
            ) ?>

        <?php endif ?>

    <?php endif ?>

    <?php /* error */ ?>
    <?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?>
        <div class="text-danger small"><?php echo $this->getTemplate()->Form()->getErrorMessage($element) ?></div>
    <?php endif ?>

    <?php /* notice */ ?>
    <?php if ($this->getSendElementOption($element, 'notice') !== ''): ?>
        <div
            class="text-info"><?php echo HTML::outputString($this->getSendElementOption($element, 'notice')) ?></div>
    <?php endif ?>

    <?php /* buttons */ ?>
    <?php if ($this->getSendElementOption($element, 'buttons') !== ''): ?>
        <div>
            <?php echo implode(' ', $this->getSendElementOption($element, 'buttons')) ?>
        </div>
    <?php endif ?>

</div>
