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

        <?php $bitmask = $this->getAddElementStorage($element); ?>

        <?php if ($this->getAddElementOption($element, 'orientation') === 'horizontal'): ?>
            <div>
        <?php endif ?>

        <?php foreach ($this->getAddElementOption($element, 'data') as $key => $value): ?>

            <?php if ($this->getAddElementOption($element, 'orientation') === 'horizontal'): ?>
                <div class="form-check-inline">
            <?php endif ?>

            <div class="custom-checkbox">
                <?php if (isset($bitmask[$key]) && ($bitmask[$key] === '1')): ?><?php echo $this->getGroupMessage(
                    'log_char_true'
                ) . ' ' . HTML::outputString($value) ?><?php else: ?><?php echo $this->getGroupMessage(
                    'log_char_false'
                ) . ' ' . HTML::outputString($value) ?><?php endif ?><?php echo $this->getTemplate()->Form(
                )->drawHiddenField($element . '_' . $key, (isset($bitmask[$key]) ? 1 : 0)) ?>
            </div>

            <?php if ($this->getAddElementOption($element, 'orientation') === 'horizontal'): ?>
                </div>
            <?php endif ?>

        <?php endforeach ?>

        <?php if ($this->getAddElementOption($element, 'orientation') === 'horizontal'): ?>
            </div>
        <?php endif ?>

    <?php else: ?>

        <?php /* input */ ?>

        <?php $bitmask = $this->getAddElementStorage($element); ?>

        <?php if ($this->getAddElementOption($element, 'orientation') === 'horizontal'): ?>
            <div>
        <?php endif ?>

        <?php foreach ($this->getAddElementOption($element, 'data') as $key => $value): ?>

            <?php if ($this->getAddElementOption($element, 'orientation') === 'horizontal'): ?>
                <div class="form-check-inline">
            <?php endif; ?>

            <div class="form-check">
                <?php echo $this->getTemplate()->Form()->drawCheckBoxField(
                    $element . '_' . $key,
                    '1',
                    ((isset($bitmask[$key]) && ($bitmask[$key] === '1')) ? 1 : 0),
                    [
                        'input_parameter' => 'title="' . HTML::outputString($value) . '"',
                        'input_class' => 'form-check-input',
                    ]
                ) ?>
                <label
                    class="form-check-label<?php if ($this->getTemplate()->Form()->getErrorMessage(
                        $element
                    ) !== null
                    ): ?> text-danger<?php endif ?>"
                    for="<?php echo $element . '_' . $key ?>0"><?php echo HTML::outputString($value) ?></label>
            </div>

            <?php if ($this->getAddElementOption($element, 'orientation') === 'horizontal'): ?>
                </div>
            <?php endif ?>

        <?php endforeach ?>

        <?php if ($this->getAddElementOption($element, 'orientation') === 'horizontal'): ?>
            </div>
        <?php endif ?>

    <?php endif ?>

    <?php /* error */ ?>
    <?php if ($this->getTemplate()->Form()->getErrorMessage($element) !== null): ?>
        <div class="text-danger small"><?php echo $this->getTemplate()->Form()->getErrorMessage($element) ?></div>
    <?php endif ?>

    <?php /* notice */ ?>
    <?php if ($this->getAddElementOption($element, 'notice') !== ''): ?>
        <div
            class="text-info"><?php echo HTML::outputString($this->getAddElementOption($element, 'notice')) ?></div>
    <?php endif ?>

    <?php /* buttons */ ?>
    <?php if ($this->getAddElementOption($element, 'buttons') !== ''): ?>
        <div><?php echo implode(' ', $this->getAddElementOption($element, 'buttons')) ?></div>
    <?php endif ?>

</div>
