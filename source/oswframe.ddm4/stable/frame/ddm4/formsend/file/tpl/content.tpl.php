<?php

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license MIT License
 */

?>

<div class="form-group ddm_element_<?php echo $this->getSendElementValue($element, 'id') ?>">

	<?php /* label */ ?>
	<label class="form-label" for="<?php echo $element ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getSendElementValue($element, 'title')) ?><?php if ($this->getSendElementOption($element, 'required')===true): ?><?php echo $this->getGroupMessage('form_title_required_icon') ?><?php endif ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>

	<?php if ($this->getSendElementOption($element, 'read_only')===true): ?>

		<?php /* read only */ ?>
		<div class="form-control readonly">
			<?php if ($this->getSendElementStorage($element)!=''): ?>
				<a target="_blank" href="<?php echo $this->getSendElementStorage($element) ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getSendElementOption($element, 'text_file_view')) ?></a>
				<?php $this->getTemplate()->Form()->drawHiddenField($element, $this->getSendElementStorage($element)) ?><?php else: ?><?php echo \osWFrame\Core\HTML::outputString($this->getSendElementOption($element, 'text_blank')) ?><?php endif ?>
		</div>

	<?php else: ?>

		<?php /* input */ ?>

		<?php echo $this->getTemplate()->Form()->drawFileField($element, $this->getSendElementStorage($element), ['input_class'=>'form-control-input', 'input_errorclass'=>'is-invalid']) ?>

	<?php endif ?>

	<?php /* error */ ?>
	<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?>
		<div class="text-danger small"><?php echo $this->getTemplate()->Form()->getErrorMessage($element) ?></div>
	<?php endif ?>

	<?php /* notice */ ?>
	<?php if ($this->getSendElementOption($element, 'notice')!=''): ?>
		<div class="text-info"><?php echo \osWFrame\Core\HTML::outputString($this->getSendElementOption($element, 'notice')) ?></div>
	<?php endif ?>

	<?php /* misc */ ?>
	<?php if (($this->getDoSendElementStorage($element.$this->getSendElementOption($element, 'temp_suffix'))!='')&&($this->getSendElementOption($element, 'read_only')!==true)): ?>
		<div class="form-check">
			<?php echo $this->getTemplate()->Form()->drawCheckBoxField($element.$this->getSendElementOption($element, 'delete_suffix'), 1, 0, ['input_parameter'=>'title="'.\osWFrame\Core\HTML::outputString($this->getSendElementOption($element, 'text_file_delete')).'"', 'input_class'=>'form-check-input']) ?>
			<label class="form-check-label" for="<?php echo $element.$this->getSendElementOption($element, 'delete_suffix') ?>0"><?php echo \osWFrame\Core\HTML::outputString($this->getSendElementOption($element, 'text_file_delete')) ?></label>
		</div>
		<?php $this->getTemplate()->Form()->drawHiddenField($element.$this->getSendElementOption($element, 'temp_suffix'), $this->getDoSendElementStorage($element.$this->getSendElementOption($element, 'temp_suffix'))) ?><?php $this->getTemplate()->Form()->drawHiddenField($element, $this->getDoSendElementStorage($element)) ?><?php elseif (($this->getSendElementStorage($element)!='')&&($this->getSendElementOption($element, 'read_only')!==true)): ?>
		<div class="form-check">
			<?php echo $this->getTemplate()->Form()->drawCheckBoxField($element.$this->getSendElementOption($element, 'delete_suffix'), 1, 0, ['input_parameter'=>'title="'.\osWFrame\Core\HTML::outputString($this->getSendElementOption($element, 'text_file_delete')).'"', 'input_class'=>'form-check-input']) ?>
			<label class="form-check-label" for="<?php echo $element.$this->getSendElementOption($element, 'delete_suffix') ?>0"><?php echo \osWFrame\Core\HTML::outputString($this->getSendElementOption($element, 'text_file_delete')) ?></label>
		</div>
		<?php $this->getTemplate()->Form()->drawHiddenField($element, $this->getSendElementStorage($element)) ?>

	<?php endif ?>

	<?php /* buttons */ ?>
	<?php if (($this->getSendElementOption($element, 'buttons')!='')||(($this->getDoSendElementStorage($element.$this->getSendElementOption($element, 'temp_suffix'))!='')||($this->getSendElementStorage($element)!='')&&($this->getSendElementOption($element, 'read_only')!==true))): ?>
		<div>
			<?php if ($this->getDoSendElementStorage($element.$this->getSendElementOption($element, 'temp_suffix'))!=''): ?>
				<a target="_blank" class="btn btn-secondary btn-sm" href="<?php echo $this->getDoSendElementStorage($element.$this->getSendElementOption($element, 'temp_suffix')) ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getSendElementOption($element, 'text_file_view')) ?></a>
			<?php elseif ($this->getSendElementStorage($element)!=''): ?>
				<a target="_blank" class="btn btn-secondary btn-sm" href="<?php echo $this->getSendElementStorage($element) ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getSendElementOption($element, 'text_file_view')) ?></a>
				<?php $this->getTemplate()->Form()->drawHiddenField($element, $this->getSendElementStorage($element)) ?><?php endif ?>
			<?php if ($this->getSendElementOption($element, 'edit_enabled')): ?>
				<a class="btn btn-secondary btn-sm" target="_blank" id="ddm_element_<?php echo $this->getName() ?>_<?php echo $element ?>_crop_link" href="<?php echo $this->getTemplate()->buildhrefLink('current', 'vistool='.$this->getGroupOption('tool', 'data').'&vispage=vis_api&action=ddm4_popup&function=ddm4_fileimage_edit&ddm_element='.$ddm_group.'_'.$element) ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getSendElementOption($element, 'text_file_edit')) ?></a>
			<?php endif ?>
			<?php if ($this->getSendElementOption($element, 'buttons')!=''): ?>

				<?php echo implode(' ', $this->getSendElementOption($element, 'buttons')) ?>

			<?php endif ?>
		</div>
	<?php endif ?>

</div>