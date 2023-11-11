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

        <?php /* read only */ ?><?php $multicheckbox = [] ?><?php if ($this->getSendElementStorage(
            $element
        ) !== ''
        ): ?><?php $multicheckbox = explode(
            $this->getSendElementOption($element, 'separator'),
            $this->getSendElementStorage($element)
        ) ?><?php endif ?><?php if ($this->getSendElementOption(
            $element,
            'orientation'
        ) === 'horizontal'
        ): ?><div><?php endif ?><?php foreach ($this->getSendElementOption(
            $element,
            'data'
        ) as $key => $value): ?><?php if ($this->getSendElementOption(
            $element,
            'orientation'
        ) === 'horizontal'
        ): ?><div class="form-check-inline"><?php endif ?>
            <div class="custom-checkbox">
                <?php if (in_array($key, $multicheckbox, true)): ?><?php echo $this->getGroupMessage(
                    'log_char_true'
                ) . ' ' . HTML::outputString($value) ?><?php else: ?><?php echo $this->getGroupMessage(
                    'log_char_false'
                ) . ' ' . HTML::outputString($value) ?><?php endif ?><?php echo $this->getTemplate()->Form(
                )->drawHiddenField($element . '_' . $key, (isset($bitmask[$key]) ? 1 : 0)) ?>
            </div>
            <?php if ($this->getSendElementOption(
                $element,
                'orientation'
            ) === 'horizontal'
            ): ?></div><?php endif ?><?php endforeach ?><?php if ($this->getSendElementOption(
                $element,
                'orientation'
            ) === 'horizontal'
            ): ?></div><?php endif ?>

    <?php else: ?>

        <?php /* input */ ?><?php $multicheckbox = [] ?><?php if ($this->getSendElementStorage(
            $element
        ) !== ''
        ): ?><?php $multicheckbox = explode(
            $this->getSendElementOption($element, 'separator'),
            $this->getSendElementStorage($element)
        ) ?><?php endif ?><?php if ($this->getSendElementOption(
            $element,
            'orientation'
        ) === 'horizontal'
        ): ?><div><?php endif ?><?php foreach ($this->getSendElementOption(
            $element,
            'data'
        ) as $key => $value): ?><?php if ($this->getSendElementOption(
            $element,
            'orientation'
        ) === 'horizontal'
        ): ?><div class="form-check-inline"><?php endif; ?>
            <div class="form-check">
                <?php echo $this->getTemplate()->Form()->drawCheckBoxField(
                    $element . '_' . $key,
                    '1',
                    ((in_array($key, $multicheckbox, true)) ? 1 : 0),
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
            <?php if ($this->getSendElementOption(
                $element,
                'orientation'
            ) === 'horizontal'
            ): ?></div><?php endif ?><?php endforeach ?><?php if ($this->getSendElementOption(
                $element,
                'orientation'
            ) === 'horizontal'
            ): ?></div><?php endif ?>

    <?php endif ?>

    <?php /* error */ ?>
    <?php if ($this->getTemplate()->Form()->getErrorMessage($element) !== null): ?>
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
