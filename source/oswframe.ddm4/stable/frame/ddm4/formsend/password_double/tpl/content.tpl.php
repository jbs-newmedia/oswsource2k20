<?php

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

?>

<div class="form-group ddm_element_<?php echo $this->getSendElementValue($element, 'id') ?>">

	<?php /* label */ ?>
	<label for="<?php echo $element ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getSendElementValue($element, 'title')) ?><?php if ($this->getSendElementOption($element, 'required')===true): ?><?php echo $this->getGroupMessage('form_title_required_icon') ?><?php endif ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>

	<?php if ($this->getSendElementOption($element, 'read_only')===true): ?>

		<?php /* read only */ ?>
		<div class="form-control readonly"><?php echo \osWFrame\Core\HTML::outputString($this->getSendElementOption($element, 'text_hidden')) ?></div>

	<?php else: ?>

		<?php /* input */ ?><?php echo $this->getTemplate()->Form()->drawPasswordField($element, $this->getSendElementStorage($element), ['input_class'=>'form-control', 'input_errorclass'=>'is-invalid']); ?>

	<?php endif ?>

	<?php /* error */ ?>
	<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?>
		<div class="text-danger small"><?php echo $this->getTemplate()->Form()->getErrorMessage($element) ?></div>
	<?php endif ?>

	<?php /* notice */ ?>
	<?php if ($this->getSendElementOption($element, 'notice')!=''): ?>
		<div class="text-info"><?php echo \osWFrame\Core\HTML::outputString($this->getSendElementOption($element, 'notice')) ?></div>
	<?php endif ?>

	<?php /* buttons */ ?>
	<?php if ($this->getSendElementOption($element, 'buttons')!=''): ?>
		<div>
			<?php echo implode(' ', $this->getSendElementOption($element, 'buttons')) ?>
		</div>
	<?php endif ?>

</div>

<div class="form-group ddm_element_<?php echo $this->getSendElementValue($element.'_double', 'id_double') ?>">

	<?php /* label */ ?>
	<label for="<?php echo $element.'_double' ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getSendElementOption($element, 'title_double')) ?><?php if ($this->getSendElementOption($element, 'required')===true): ?><?php echo $this->getGroupMessage('form_title_required_icon') ?><?php endif ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>

	<?php if ($this->getSendElementOption($element, 'read_only')===true): ?>

		<?php /* read only */ ?>
		<div class="form-control readonly"><?php echo \osWFrame\Core\HTML::outputString($this->getSendElementOption($element, 'text_hidden')) ?></div>

	<?php else: ?>

		<?php /* input */ ?><?php echo $this->getTemplate()->Form()->drawPasswordField($element.'_double', $this->getSendElementStorage($element), ['input_class'=>'form-control', 'input_errorclass'=>'is-invalid']); ?>

	<?php endif ?>

	<?php /* error */ ?>
	<?php if ($this->getTemplate()->Form()->getErrorMessage($element.'_double')): ?>
		<div class="text-danger small"><?php echo $this->getTemplate()->Form()->getErrorMessage($element.'_double') ?></div>
	<?php endif ?>

	<?php /* notice */ ?>
	<?php if ($this->getSendElementOption($element.'_double', 'notice')!=''): ?>
		<div class="text-info"><?php echo \osWFrame\Core\HTML::outputString($this->getSendElementOption($element.'_double', 'notice')) ?></div>
	<?php endif ?>

	<?php /* buttons */ ?>
	<?php if ($this->getSendElementOption($element.'_double', 'buttons')!=''): ?>
		<div>
			<?php echo implode(' ', $this->getSendElementOption($element.'_double', 'buttons')) ?>
		</div>
	<?php endif ?>

</div>