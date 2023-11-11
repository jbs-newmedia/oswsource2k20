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

<div class="form-group ddm_element_<?php echo $this->getEditElementValue($element, 'id') ?>">

    <?php /* label */ ?>

    <label class="form-label"
           for="<?php echo $element ?>"><?php echo HTML::outputString(
               $this->getEditElementValue($element, 'title')
           ) ?><?php if ($this->getEditElementOption($element, 'required') === true): ?><?php echo $this->getGroupMessage(
               'form_title_required_icon'
           ) ?><?php endif ?><?php echo $this->getGroupMessage(
               'form_title_closer'
           ) ?></label><?php if ($this->getEditElementOption($element, 'read_only') === true): ?>

        <?php /* read only */ ?>

        <div class="form-control readonly">

            <?php if (($this->getEditElementStorage($element) === '') || ($this->getEditElementStorage(
                $element
            ) === '00000000')
            ): ?>
                ---
            <?php else: ?>

                <?php echo $this->getTemplate()->Form()->drawHiddenField(
                    $element . '_day',
                    substr($this->getEditElementStorage($element), 6, 2)
                ) ?><?php echo $this->getTemplate()->Form()->drawHiddenField(
                    $element . '_month',
                    substr($this->getEditElementStorage($element), 4, 2)
                ) ?><?php echo $this->getTemplate()->Form()->drawHiddenField(
                    $element . '_year',
                    substr($this->getEditElementStorage($element), 0, 4)
                ) ?>

                <?php if ($this->getEditElementOption($element, 'month_asname') === true): ?>

                    <?php echo \osWFrame\Core\DateTime::strftime(
                        str_replace('%m.', ' %B ', $this->getEditElementOption($element, 'date_format')),
                        mktime(
                            12,
                            0,
                            0,
                            substr($this->getEditElementStorage($element), 4, 2),
                            substr($this->getEditElementStorage($element), 6, 2),
                            substr($this->getEditElementStorage($element), 0, 4)
                        )
                    ) ?><?php else: ?><?php echo \osWFrame\Core\DateTime::strftime(
                        $this->getEditElementOption($element, 'date_format'),
                        mktime(
                            12,
                            0,
                            0,
                            substr($this->getEditElementStorage($element), 4, 2),
                            substr($this->getEditElementStorage($element), 6, 2),
                            substr($this->getEditElementStorage($element), 0, 4)
                        )
                    ) ?>

                <?php endif ?>

            <?php endif ?>

        </div>

    <?php else: ?>

        <?php /* input */ ?>

        <?php $data = $this->getEditElementOption($element, 'data') ?>

        <?php $date_format = $this->getEditElementOption($element, 'date_format') ?>

        <?php $c = substr_count($date_format, '%') ?>

        <?php $d = bcdiv(12, $c) ?>

        <?php $date_format = str_replace('%', '%osw_tmp_ddm3_%', $date_format) ?>

        <?php $date_format = str_replace('-', '', $date_format) ?>

        <?php $date_format = str_replace('.', '', $date_format) ?>

        <?php $date_format = str_replace(',', '', $date_format) ?>

        <?php $date_format = str_replace(
            '%osw_tmp_ddm3_%d',
            '<div class="col-sm-' . $d . '">' . $this->getTemplate()->Form()->drawSelectField(
                $element . '_day',
                $data['day'],
                substr($this->getEditElementStorage($element), 6, 2),
                [
                    'input_class' => 'selectpicker select-ellipsis-fix form-control',
                    'input_errorclass' => 'is-invalid',
                    'input_parameter' => ' style="width:20%;" data-style="custom-select"',
                ]
            ) . '</div>',
            $date_format
        ) ?>

        <?php $date_format = str_replace(
            '%osw_tmp_ddm3_%m',
            '<div class="col-sm-' . $d . '">' . $this->getTemplate()->Form()->drawSelectField(
                $element . '_month',
                $data['month'],
                substr($this->getEditElementStorage($element), 4, 2),
                [
                    'input_class' => 'selectpicker select-ellipsis-fix form-control',
                    'input_errorclass' => 'is-invalid',
                    'input_parameter' => ' style="width:20%;" data-style="custom-select"',
                ]
            ) . '</div>',
            $date_format
        ) ?>

        <?php $date_format = str_replace(
            '%osw_tmp_ddm3_%Y',
            '<div class="col-sm-' . $d . '">' . $this->getTemplate()->Form()->drawSelectField(
                $element . '_year',
                $data['year'],
                substr($this->getEditElementStorage($element), 0, 4),
                [
                    'input_class' => 'selectpicker select-ellipsis-fix form-control',
                    'input_errorclass' => 'is-invalid',
                    'input_parameter' => ' style="width:20%;" data-style="custom-select"',
                ]
            ) . '</div>',
            $date_format
        ) ?>

        <?php $date_format = str_replace(
            '%osw_tmp_ddm3_%y',
            '<div class="col-sm-' . $d . '">' . $this->getTemplate()->Form()->drawSelectField(
                $element . '_year',
                $data['year'],
                substr($this->getEditElementStorage($element), 0, 4),
                [
                    'input_class' => 'selectpicker select-ellipsis-fix form-control',
                    'input_errorclass' => 'is-invalid',
                    'input_parameter' => ' style="width:20%;" data-style="custom-select"',
                ]
            ) . '</div>',
            $date_format
        ) ?>

        <?php echo '<div class="form-group row">' . $date_format . '</div>'; ?>

    <?php endif ?>

    <?php /* error */ ?>

    <?php if ($this->getTemplate()->Form()->getErrorMessage($element) !== null): ?>
        <div class="text-danger small"><?php echo $this->getTemplate()->Form()->getErrorMessage($element) ?></div>
    <?php endif ?>

    <?php /* notice */ ?>

    <?php if ($this->getEditElementOption($element, 'notice') !== ''): ?>
        <div
            class="text-info"><?php echo HTML::outputString($this->getEditElementOption($element, 'notice')) ?></div>
    <?php endif ?>

    <?php /* buttons */ ?>

    <?php if ($this->getEditElementOption($element, 'buttons') !== ''): ?>
        <div><?php echo implode(' ', $this->getEditElementOption($element, 'buttons')) ?></div>
    <?php endif ?>

</div>
