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

<div class="form-group ddm_element_<?php echo $this->getDeleteElementValue($element, 'id') ?>">

    <?php /* label */ ?>

    <label class="form-label"
           for="<?php echo $element ?>"><?php echo HTML::outputString(
               $this->getDeleteElementValue($element, 'title')
           ) ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>

    <?php /* read only */ ?>

    <?php if ($this->getDeleteElementOption($element, 'blank_value') === true): ?>

        <?php $data = [
                       '' => '',
                   ] + $this->getDeleteElementOption($element, 'data'); ?>

    <?php else: ?>

        <?php $data = $this->getDeleteElementOption($element, 'data'); ?>

    <?php endif ?>

    <?php if ((isset($data[$this->getDeleteElementStorage($element)])) && ($data[$this->getDeleteElementStorage(
        $element
    )] !== '')
    ): ?>

        <div
            class="form-control readonly"><?php echo HTML::outputString(
                $data[$this->getDeleteElementStorage($element)]
            ) ?></div>

    <?php else: ?>

        <div class="form-control readonly">&nbsp;</div>

    <?php endif ?>

    <?php echo $this->getTemplate()->Form()->drawHiddenField($element, $this->getDeleteElementStorage($element)) ?>

    <?php /* error */ ?>
    <?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?>
        <div class="text-danger small"><?php echo $this->getTemplate()->Form()->getErrorMessage($element) ?></div>
    <?php endif ?>

    <?php /* notice */ ?>
    <?php if ($this->getDeleteElementOption($element, 'notice') !== ''): ?>
        <div
            class="text-info"><?php echo HTML::outputString($this->getDeleteElementOption($element, 'notice')) ?></div>
    <?php endif ?>

    <?php /* buttons */ ?>
    <?php if ($this->getDeleteElementOption($element, 'buttons') !== ''): ?>
        <div>
            <?php echo implode(' ', $this->getDeleteElementOption($element, 'buttons')) ?>
        </div>
    <?php endif ?>

</div>
