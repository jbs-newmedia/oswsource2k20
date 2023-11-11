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
 * @var array $values
 * @var \osWFrame\Core\DDM4 $this
 *
 */
use osWFrame\Core\HTML;

?>

<div class="form-group ddm_element_<?php echo $this->getSearchElementValue($element, 'id') ?>">

    <?php /* label */ ?>
    <label class="form-label"
           for="<?php echo $element ?>"><?php echo HTML::outputString(
               $this->getSearchElementValue($element, 'title')
           ) ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>

    <?php if ($this->getSearchElementOption($element, 'read_only') === true): ?>

        <?php /* read only */ ?><?php if ($this->getSearchElementOption(
            $element,
            'blank_value'
        ) === true
        ): ?><?php $data = [
            '' => '',
        ] + $this->getSearchElementOption(
            $element,
            'data'
        ); ?><?php else: ?><?php $data = $this->getSearchElementOption(
            $element,
            'data'
        ); ?><?php endif ?><?php if (isset($data[$this->getSearchElementStorage($element)])): ?>
            <div
                class="form-control readonly"><?php echo HTML::outputString(
                    $data[$this->getSearchElementStorage($element)]
                ) ?></div>
        <?php else: ?>
            <div class="form-control readonly">&nbsp;</div>
        <?php endif ?><?php echo $this->getTemplate()->Form()->drawHiddenField(
            $element,
            $this->getSearchElementStorage($element)
        ) ?>

    <?php else: ?>

        <?php /* input */ ?><?php if ($this->getSearchElementOption(
            $element,
            'blank_value'
        ) === true
        ): ?><?php echo $this->getTemplate()->Form()->drawSelectField(
            $element,
            [
                '%' => $values['options']['text_all'],
            ] + [
                '' => ' ',
            ] + $this->getSearchElementOption($element, 'data'),
            $this->getSearchElementStorage($element),
            [
                'input_class' => 'selectpicker form-control',
                'input_errorclass' => 'is-invalid',
                'input_parameter' => ' data-style="custom-select"',
            ]
        ) ?><?php else: ?><?php echo $this->getTemplate()->Form()->drawSelectField(
            $element,
            [
                '%' => $values['options']['text_all'],
            ] + $this->getSearchElementOption($element, 'data'),
            $this->getSearchElementStorage($element),
            [
                'input_class' => 'selectpicker form-control',
                'input_errorclass' => 'is-invalid',
                'input_parameter' => ' data-style="custom-select" data-size="' . $this->getSearchElementOption(
                    $element,
                    'data_size'
                ) . '" data-live-search="' . $this->getSearchElementOption($element, 'live_search') . '"',
            ]
        ) ?><?php endif ?>

    <?php endif ?>

    <?php /* error */ ?>
    <?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?>
        <div class="text-danger small"><?php echo $this->getTemplate()->Form()->getErrorMessage($element) ?></div>
    <?php endif ?>

    <?php /* notice */ ?>
    <?php if ($this->getSearchElementOption($element, 'notice') !== ''): ?>
        <div
            class="text-info"><?php echo HTML::outputString($this->getSearchElementOption($element, 'notice')) ?></div>
    <?php endif ?>

    <?php /* buttons */ ?>
    <?php if ($this->getSearchElementOption($element, 'buttons') !== ''): ?>
        <div>
            <?php echo implode(' ', $this->getSearchElementOption($element, 'buttons')) ?>
        </div>
    <?php endif ?>

</div>
