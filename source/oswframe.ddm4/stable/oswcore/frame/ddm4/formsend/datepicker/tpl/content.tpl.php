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
        <div class="form-control readonly">
            <?php if (($this->getSendElementStorage($element) === '') || ($this->getSendElementStorage(
                $element
            ) === '0') || ($this->getSendElementStorage(
                $element
            ) === '00000000') || ($this->getSendElementStorage($element) === '0')
            ): ?>
                ---
            <?php else: ?><?php echo $this->getTemplate()->Form()->drawHiddenField(
                $element,
                \osWFrame\Core\DateTime::strftime(
                    $this->getSendElementOption($element, 'format'),
                    mktime(
                        12,
                        0,
                        0,
                        substr($this->getSendElementStorage($element), 4, 2),
                        substr($this->getSendElementStorage($element), 6, 2),
                        substr($this->getSendElementStorage($element), 0, 4)
                    )
                )
            ) ?><?php if ($this->getSendElementOption(
                $element,
                'month_asname'
            ) === true
            ): ?><?php echo \osWFrame\Core\DateTime::strftime(
                str_replace('%m.', ' %B ', $this->getSendElementOption($element, 'format')),
                mktime(
                    12,
                    0,
                    0,
                    substr($this->getSendElementStorage($element), 4, 2),
                    substr($this->getSendElementStorage($element), 6, 2),
                    substr($this->getSendElementStorage($element), 0, 4)
                )
            ) ?><?php else: ?><?php echo \osWFrame\Core\DateTime::strftime(
                $this->getSendElementOption($element, 'format'),
                mktime(
                    12,
                    0,
                    0,
                    substr($this->getSendElementStorage($element), 4, 2),
                    substr($this->getSendElementStorage($element), 6, 2),
                    substr($this->getSendElementStorage($element), 0, 4)
                )
            ) ?><?php endif ?><?php endif ?>
        </div>
        <?php echo $this->getTemplate()->Form()->drawHiddenField($element, $this->getSendElementStorage($element)) ?>

    <?php else: ?>

        <?php /* input */ ?><?php if (($this->getSendElementStorage($element) === '') || ($this->getSendElementStorage(
            $element
        ) === '0') || ($this->getSendElementStorage($element) === '00000000') || ($this->getSendElementStorage(
            $element
        ) === '0')
        ): ?><?php echo $this->getTemplate()->Form()->drawTextField($element, '', [
            'input_class' => 'form-control',
            'input_errorclass' => 'is-invalid',
        ]); ?><?php else: ?><?php echo $this->getTemplate()->Form()->drawTextField(
            $element,
            \osWFrame\Core\DateTime::strftime(
                $this->getSendElementOption($element, 'format'),
                mktime(
                    12,
                    0,
                    0,
                    substr($this->getSendElementStorage($element), 4, 2),
                    substr($this->getSendElementStorage($element), 6, 2),
                    substr($this->getSendElementStorage($element), 0, 4)
                )
            ),
            [
                'input_class' => 'form-control',
                'input_errorclass' => 'is-invalid',
            ]
        ); ?><?php endif ?>

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
