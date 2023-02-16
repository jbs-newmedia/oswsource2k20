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

<div class="form-group ddm_element_<?php echo $this->getAddElementValue($element, 'id') ?>">

	<?php /* label */ ?>

	<label class="form-label" for="<?php echo $element ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getAddElementValue($element, 'title')) ?><?php if ($this->getAddElementOption($element, 'required')===true): ?><?php echo $this->getGroupMessage('form_title_required_icon') ?><?php endif ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>

	<?php if ($this->getAddElementOption($element, 'read_only')===true): ?>

		<?php /* read only */ ?>

		<?php if ($this->getAddElementOption($element, 'blank_value')===true): ?>

			<?php $data=[''=>'']+$this->getAddElementOption($element, 'data'); ?>

		<?php else: ?>

			<?php $data=$this->getAddElementOption($element, 'data'); ?>

		<?php endif ?>

		<?php if ((isset($data[$this->getAddElementStorage($element)]))&&($data[$this->getAddElementStorage($element)]!='')): ?>

			<div class="form-control readonly"><?php echo \osWFrame\Core\HTML::outputString($data[$this->getAddElementStorage($element)]) ?></div>

		<?php else: ?>

			<div class="form-control readonly">&nbsp;</div>

		<?php endif ?>

		<?php echo $this->getTemplate()->Form()->drawHiddenField($element, $this->getAddElementStorage($element)) ?>

	<?php else: ?>

		<?php /* input */ ?>

		<?php if ($this->getAddElementOption($element, 'blank_value')===true): ?>

			<?php if ($this->getAddElementValidation($element, 'module')=='integer'): ?>

				<?php echo $this->getTemplate()->Form()->drawSelectField($element, [0=>' ']+$this->getAddElementOption($element, 'data'), $this->getAddElementStorage($element), ['input_class'=>'selectpicker select-ellipsis-fix form-control', 'input_errorclass'=>'is-invalid', 'input_parameter'=>' data-style="custom-select" data-size="'.$this->getAddElementOption($element, 'data_size').'" data-live-search="'.$this->getAddElementOption($element, 'live_search').'" title="'.$this->getAddElementOption($element, 'data_choose').'"']) ?>

			<?php else: ?>

				<?php echo $this->getTemplate()->Form()->drawSelectField($element, [''=>' ']+$this->getAddElementOption($element, 'data'), $this->getAddElementStorage($element), ['input_class'=>'selectpicker select-ellipsis-fix form-control', 'input_errorclass'=>'is-invalid', 'input_parameter'=>' data-style="custom-select" data-size="'.$this->getAddElementOption($element, 'data_size').'" data-live-search="'.$this->getAddElementOption($element, 'live_search').'" title="'.$this->getAddElementOption($element, 'data_choose').'"']) ?>

			<?php endif ?>

		<?php else: ?>

			<?php echo $this->getTemplate()->Form()->drawSelectField($element, $this->getAddElementOption($element, 'data'), $this->getAddElementStorage($element), ['input_class'=>'selectpicker select-ellipsis-fix form-control', 'input_errorclass'=>'is-invalid', 'input_parameter'=>' data-style="custom-select" data-size="'.$this->getAddElementOption($element, 'data_size').'" data-live-search="'.$this->getAddElementOption($element, 'live_search').'" title="'.$this->getAddElementOption($element, 'data_choose').'"']) ?>

		<?php endif ?>

	<?php endif ?>

	<?php /* error */ ?>
	<?php if ($this->getTemplate()->Form()->getErrorMessage($element)!==null): ?>
		<div class="text-danger small"><?php echo $this->getTemplate()->Form()->getErrorMessage($element) ?></div>
	<?php endif ?>

	<?php /* notice */ ?>
	<?php if ($this->getAddElementOption($element, 'notice')!=''): ?>
		<div class="text-info"><?php echo \osWFrame\Core\HTML::outputString($this->getAddElementOption($element, 'notice')) ?></div>
	<?php endif ?>

	<?php /* buttons */ ?>
	<?php if ($this->getAddElementOption($element, 'buttons')!=''): ?>
		<div>
			<?php echo implode(' ', $this->getAddElementOption($element, 'buttons')) ?>
		</div>
	<?php endif ?>

</div>