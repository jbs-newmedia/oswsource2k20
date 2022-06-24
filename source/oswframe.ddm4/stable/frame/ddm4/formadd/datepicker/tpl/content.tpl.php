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

		<div class="form-control readonly">

			<?php if (($this->getAddElementStorage($element)=='')||($this->getAddElementStorage($element)=='0')||($this->getAddElementStorage($element)=='00000000')||($this->getAddElementStorage($element)=='0')): ?>
				---
			<?php else: ?>

				<?php echo $this->getTemplate()->Form()->drawHiddenField($element, \osWFrame\Core\DateTime::strftime($this->getAddElementOption($element, 'format'), mktime(12, 0, 0, substr($this->getAddElementStorage($element), 4, 2), substr($this->getAddElementStorage($element), 6, 2), substr($this->getAddElementStorage($element), 0, 4)))) ?><?php if ($this->getAddElementOption($element, 'month_asname')===true): ?><?php echo \osWFrame\Core\DateTime::strftime(str_replace('%m.', ' %B ', $this->getAddElementOption($element, 'format')), mktime(12, 0, 0, substr($this->getAddElementStorage($element), 4, 2), substr($this->getAddElementStorage($element), 6, 2), substr($this->getAddElementStorage($element), 0, 4))) ?><?php echo $this->getTemplate()->Form()->drawHiddenField($element, \osWFrame\Core\DateTime::strftime(str_replace('%m.', ' %B ', $this->getAddElementOption($element, 'format')), mktime(12, 0, 0, substr($this->getAddElementStorage($element), 4, 2), substr($this->getAddElementStorage($element), 6, 2), substr($this->getAddElementStorage($element), 0, 4)))) ?><?php else: ?><?php echo \osWFrame\Core\DateTime::strftime($this->getAddElementOption($element, 'format'), mktime(12, 0, 0, substr($this->getAddElementStorage($element), 4, 2), substr($this->getAddElementStorage($element), 6, 2), substr($this->getAddElementStorage($element), 0, 4))) ?><?php echo $this->getTemplate()->Form()->drawHiddenField($element, \osWFrame\Core\DateTime::strftime($this->getAddElementOption($element, 'format'), mktime(12, 0, 0, substr($this->getAddElementStorage($element), 4, 2), substr($this->getAddElementStorage($element), 6, 2), substr($this->getAddElementStorage($element), 0, 4)))) ?>

				<?php endif ?>

			<?php endif ?>
		</div>

	<?php else: ?>

		<?php /* input */ ?>

		<?php if (($this->getAddElementStorage($element)=='')||($this->getAddElementStorage($element)=='0')||($this->getAddElementStorage($element)=='00000000')||($this->getAddElementStorage($element)=='0')): ?>

			<?php echo $this->getTemplate()->Form()->drawTextField($element, '', ['input_class'=>'form-control', 'input_errorclass'=>'is-invalid']); ?>

		<?php else: ?>

			<?php echo $this->getTemplate()->Form()->drawTextField($element, \osWFrame\Core\DateTime::strftime($this->getAddElementOption($element, 'format'), mktime(12, 0, 0, substr($this->getAddElementStorage($element), 4, 2), substr($this->getAddElementStorage($element), 6, 2), substr($this->getAddElementStorage($element), 0, 4))), ['input_class'=>'form-control', 'input_errorclass'=>'is-invalid']); ?>

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
		<div><?php echo implode(' ', $this->getAddElementOption($element, 'buttons')) ?></div>
	<?php endif ?>

</div>