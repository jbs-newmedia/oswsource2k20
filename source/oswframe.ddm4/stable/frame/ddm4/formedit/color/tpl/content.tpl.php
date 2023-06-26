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
		<div class="form-control readonly"><?php echo \osWFrame\Core\HTML::outputString($this->getEditElementStorage($element)); ?></div>		<?php echo $this->getTemplate()->Form()->drawHiddenField($element, $this->getEditElementStorage($element)) ?>

	<?php else: ?>

	<?php /* input */ ?>

		<div class="input-group">
			<?php echo $this->getTemplate()->Form()->drawInputField($element.'_helper', $this->getEditElementStorage($element), ['input_class'=>'form-control form-control-color w-25', 'input_errorclass'=>'is-invalid'], 'color'); ?>

			<?php echo $this->getTemplate()->Form()->drawTextField($element, $this->getEditElementStorage($element), ['input_class'=>'form-control w-75', 'input_errorclass'=>'is-invalid']); ?>
		</div>

		<script>
			$('#<?php echo $element?>_helper').change(function () {
				$('#<?php echo $element?>').val($('#<?php echo $element?>_helper').val());
			});
			$('#<?php echo $element?>').change(function () {
				$('#<?php echo $element?>_helper').val($('#<?php echo $element?>').val());
			});
		</script>

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