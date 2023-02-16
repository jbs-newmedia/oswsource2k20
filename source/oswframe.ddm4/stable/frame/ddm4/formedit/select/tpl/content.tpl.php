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

<div class="form-group ddm_element_<?php echo $this->getEditElementValue($element, 'id') ?>">

	<?php /* label */ ?>
	<label class="form-label" for="<?php echo $element ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getEditElementValue($element, 'title')) ?><?php if ($this->getEditElementOption($element, 'required')===true): ?><?php echo $this->getGroupMessage('form_title_required_icon') ?><?php endif ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>

	<?php if ($this->getEditElementOption($element, 'read_only')===true): ?>

		<?php /* read only */ ?>

		<?php if ($this->getEditElementOption($element, 'blank_value')===true): ?>

			<?php $data=[''=>'']+$this->getEditElementOption($element, 'data'); ?>

		<?php else: ?>

			<?php $data=$this->getEditElementOption($element, 'data'); ?>

		<?php endif ?>

		<?php if ((isset($data[$this->getEditElementStorage($element)]))&&($data[$this->getEditElementStorage($element)]!='')): ?>

			<div class="form-control readonly"><?php echo \osWFrame\Core\HTML::outputString($data[$this->getEditElementStorage($element)]) ?></div>

		<?php else: ?>

			<div class="form-control readonly">&nbsp;</div>

		<?php endif ?>

		<?php echo $this->getTemplate()->Form()->drawHiddenField($element, $this->getEditElementStorage($element)) ?>

	<?php else: ?>

		<?php /* input */ ?>

		<?php if ($this->getEditElementOption($element, 'blank_value')===true): ?>

			<?php if ($this->getEditElementValidation($element, 'module')=='integer'): ?>

				<?php echo $this->getTemplate()->Form()->drawSelectField($element, [0=>' ']+$this->getEditElementOption($element, 'data'), $this->getEditElementStorage($element), ['input_class'=>'selectpicker select-ellipsis-fix form-control', 'input_errorclass'=>'is-invalid', 'input_parameter'=>' data-style="custom-select" data-size="'.$this->getEditElementOption($element, 'data_size').'" data-live-search="'.$this->getEditElementOption($element, 'live_search').'" title="'.$this->getEditElementOption($element, 'data_choose').'"']) ?>

			<?php else: ?>

				<?php echo $this->getTemplate()->Form()->drawSelectField($element, [''=>' ']+$this->getEditElementOption($element, 'data'), $this->getEditElementStorage($element), ['input_class'=>'selectpicker select-ellipsis-fix form-control', 'input_errorclass'=>'is-invalid', 'input_parameter'=>' data-style="custom-select" data-size="'.$this->getEditElementOption($element, 'data_size').'" data-live-search="'.$this->getEditElementOption($element, 'live_search').'" title="'.$this->getEditElementOption($element, 'data_choose').'"']) ?>

			<?php endif ?>

		<?php else: ?>

			<?php echo $this->getTemplate()->Form()->drawSelectField($element, $this->getEditElementOption($element, 'data'), $this->getEditElementStorage($element), ['input_class'=>'selectpicker select-ellipsis-fix form-control', 'input_errorclass'=>'is-invalid', 'input_parameter'=>' data-style="custom-select" data-size="'.$this->getEditElementOption($element, 'data_size').'" data-live-search="'.$this->getEditElementOption($element, 'live_search').'" title="'.$this->getEditElementOption($element, 'data_choose').'"']) ?>

		<?php endif ?>

	<?php endif ?>

	<?php /* error */ ?>
	<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?>
		<div class="text-danger small"><?php echo $this->getTemplate()->Form()->getErrorMessage($element) ?></div>
	<?php endif ?>

	<?php /* notice */ ?>
	<?php if ($this->getEditElementOption($element, 'notice')!=''): ?>
		<div class="text-info"><?php echo \osWFrame\Core\HTML::outputString($this->getEditElementOption($element, 'notice')) ?></div>
	<?php endif ?>

	<?php /* buttons */ ?>
	<?php if ($this->getEditElementOption($element, 'buttons')!=''): ?>
		<div>
			<?php echo implode(' ', $this->getEditElementOption($element, 'buttons')) ?>
		</div>
	<?php endif ?>

</div>