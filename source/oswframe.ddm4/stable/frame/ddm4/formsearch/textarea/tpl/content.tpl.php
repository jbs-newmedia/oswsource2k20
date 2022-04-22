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

<div class="form-group ddm_element_<?php echo $this->getSearchElementValue($element, 'id') ?>">

	<?php /* label */ ?>
	<label class="form-label" for="<?php echo $element ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getSearchElementValue($element, 'title')) ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>

	<?php if ($this->getSearchElementOption($element, 'read_only')===true): ?>

		<?php /* read only */ ?>
		<div class="form-control readonly"><?php echo \osWFrame\Core\HTML::outputString($this->getSearchElementStorage($element)); ?></div>
		<?php echo $this->getTemplate()->Form()->drawHiddenField($element, $this->getSearchElementStorage($element)) ?>

	<?php else: ?>

		<?php /* input */ ?><?php echo $this->getTemplate()->Form()->drawTextField($element, $this->getSearchElementStorage($element), ['input_class'=>'form-control', 'input_errorclass'=>'is-invalid']); ?>

	<?php endif ?>

	<?php /* error */ ?>
	<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?>
		<div class="text-danger small"><?php echo $this->getTemplate()->Form()->getErrorMessage($element) ?></div>
	<?php endif ?>

	<?php /* notice */ ?>
	<?php if ($this->getSearchElementOption($element, 'notice')!=''): ?>
		<div class="text-info"><?php echo \osWFrame\Core\HTML::outputString($this->getSearchElementOption($element, 'notice')) ?></div>
	<?php endif ?>

	<?php /* buttons */ ?>
	<?php if ($this->getSearchElementOption($element, 'buttons')!=''): ?>
		<div>
			<?php echo implode(' ', $this->getSearchElementOption($element, 'buttons')) ?>
		</div>
	<?php endif ?>

</div>