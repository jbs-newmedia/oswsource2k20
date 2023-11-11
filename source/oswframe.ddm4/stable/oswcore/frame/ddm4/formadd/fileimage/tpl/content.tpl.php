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
 * @var string $ddm_group
 * @var \osWFrame\Core\DDM4 $this
 *
 */

use osWFrame\Core\HTML;

?>

<div class="form-group ddm_element_<?php echo $this->getAddElementValue($element, 'id') ?>">

    <?php /* label */ ?>
    <label class="form-label"
           for="<?php echo $element ?>"><?php echo HTML::outputString(
               $this->getAddElementValue($element, 'title')
           ) ?><?php if ($this->getAddElementOption($element, 'required') === true): ?><?php echo $this->getGroupMessage(
               'form_title_required_icon'
           ) ?><?php endif ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>

    <?php if ($this->getAddElementOption($element, 'read_only') === true): ?>

        <?php /* read only */ ?>
        <div class="form-control readonly">
            <?php if ($this->getAddElementStorage($element) !== ''): ?>
                <a target="_blank"
                   href="<?php echo $this->getAddElementStorage($element) ?>"><?php echo HTML::outputString(
                       $this->getAddElementOption($element, 'text_file_view')
                   ) ?></a>
                <?php $this->getTemplate()->Form()->drawHiddenField(
                    $element,
                    $this->getAddElementStorage($element)
                ) ?><?php else: ?><?php echo HTML::outputString(
                    $this->getAddElementOption($element, 'text_blank')
                ) ?><?php endif ?>
        </div>

    <?php else: ?>

        <?php /* input */ ?>

        <?php echo $this->getTemplate()->Form()->drawFileField($element, $this->getAddElementStorage($element), [
            'input_class' => 'form-control-input',
            'input_errorclass' => 'is-invalid',
        ]) ?>

    <?php endif ?>

    <?php /* error */ ?>
    <?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?>
        <div class="text-danger small"><?php echo $this->getTemplate()->Form()->getErrorMessage($element) ?></div>
    <?php endif ?>

    <?php /* notice */ ?>
    <?php if ($this->getAddElementOption($element, 'notice') !== ''): ?>
        <div
            class="text-info"><?php echo HTML::outputString($this->getAddElementOption($element, 'notice')) ?></div>
    <?php endif ?>

    <?php /* misc */ ?>
    <?php if (($this->getDoAddElementStorage(
        $element . $this->getAddElementOption($element, 'temp_suffix')
    ) !== '') && ($this->getAddElementOption($element, 'read_only') !== true)
    ): ?>
        <div class="form-check">
            <?php echo $this->getTemplate()->Form()->drawCheckBoxField(
                $element . $this->getAddElementOption($element, 'delete_suffix'),
                1,
                0,
                [
                    'input_parameter' => 'title="' . HTML::outputString(
                        $this->getAddElementOption($element, 'text_file_delete')
                    ) . '"',
                    'input_class' => 'form-check-input',
                ]
            ) ?>
            <label class="form-check-label"
                   for="<?php echo $element . $this->getAddElementOption(
                       $element,
                       'delete_suffix'
                   ) ?>0"><?php echo HTML::outputString(
                       $this->getAddElementOption($element, 'text_file_delete')
                   ) ?></label>
        </div>
        <?php $this->getTemplate()->Form()->drawHiddenField(
            $element . $this->getAddElementOption($element, 'temp_suffix'),
            $this->getDoAddElementStorage($element . $this->getAddElementOption($element, 'temp_suffix'))
        ) ?><?php $this->getTemplate()->Form()->drawHiddenField(
            $element,
            $this->getDoAddElementStorage($element)
        ) ?><?php elseif (($this->getAddElementStorage($element) !== '') && ($this->getAddElementOption(
            $element,
            'read_only'
        ) !== true)
        ): ?>
        <div class="form-check">
            <?php echo $this->getTemplate()->Form()->drawCheckBoxField(
                $element . $this->getAddElementOption($element, 'delete_suffix'),
                1,
                0,
                [
                    'input_parameter' => 'title="' . HTML::outputString(
                        $this->getAddElementOption($element, 'text_file_delete')
                    ) . '"',
                    'input_class' => 'form-check-input',
                ]
            ) ?>
            <label class="form-check-label"
                   for="<?php echo $element . $this->getAddElementOption(
                       $element,
                       'delete_suffix'
                   ) ?>0"><?php echo HTML::outputString(
                       $this->getAddElementOption($element, 'text_file_delete')
                   ) ?></label>
        </div>
        <?php $this->getTemplate()->Form()->drawHiddenField($element, $this->getAddElementStorage($element)) ?>

    <?php endif ?>

    <?php /* buttons */ ?>
    <?php if (($this->getAddElementOption($element, 'buttons') !== '') || (($this->getDoAddElementStorage(
        $element . $this->getAddElementOption($element, 'temp_suffix')
    ) !== '') || ($this->getAddElementStorage($element) !== '') && ($this->getAddElementOption(
        $element,
        'read_only'
    ) !== true))
    ): ?>
        <div>
            <?php if ($this->getDoAddElementStorage(
                $element . $this->getAddElementOption($element, 'temp_suffix')
            ) !== ''
            ): ?>
                <a target="_blank" class="btn btn-secondary btn-sm"
                   href="<?php echo $this->getDoAddElementStorage(
                       $element . $this->getAddElementOption($element, 'temp_suffix')
                   ) ?>"><?php echo HTML::outputString($this->getAddElementOption($element, 'text_file_view')) ?></a>
            <?php elseif ($this->getAddElementStorage($element) !== ''): ?>
                <a target="_blank" class="btn btn-secondary btn-sm"
                   href="<?php echo $this->getAddElementStorage($element) ?>"><?php echo HTML::outputString(
                       $this->getAddElementOption($element, 'text_file_view')
                   ) ?></a>
                <?php $this->getTemplate()->Form()->drawHiddenField(
                    $element,
                    $this->getAddElementStorage($element)
                ) ?><?php endif ?>
            <?php if ($this->getAddElementOption($element, 'edit_enabled')): ?>
                <a class="btn btn-secondary btn-sm" target="_blank"
                   id="ddm_element_<?php echo $this->getName() ?>_<?php echo $element ?>_crop_link"
                   href="<?php echo $this->getTemplate()->buildhrefLink(
                       'current',
                       'vistool=' . $this->getGroupOption(
                           'tool',
                           'data'
                       ) . '&vispage=vis_api&action=ddm4_popup&function=ddm4_fileimage_edit&ddm_element=' . $ddm_group . '_' . $element
                   ) ?>"><?php echo HTML::outputString($this->getAddElementOption($element, 'text_file_edit')) ?></a>
            <?php endif ?>
            <?php if ($this->getAddElementOption($element, 'buttons') !== ''): ?>

                <?php echo implode(' ', $this->getAddElementOption($element, 'buttons')) ?>

            <?php endif ?>
        </div>
    <?php endif ?>

</div>
