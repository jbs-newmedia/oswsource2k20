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

<div class="form-group ddm_element_<?php echo $this->getAddElementValue($element, 'id') ?>">

	<?php /* label */ ?>
	<label for="<?php echo $element ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getAddElementValue($element, 'title')) ?><?php if ($this->getAddElementOption($element, 'required')===true): ?><?php echo $this->getGroupMessage('form_title_required_icon') ?><?php endif ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>

	<?php /* read only */ ?>
	<div class="form-control readonly">
		<?php if (($this->getAddElementStorage($element)=='')||($this->getAddElementStorage($element)=='00000000')): ?>
			---
		<?php else: ?><?php echo $this->getTemplate()->Form()->drawHiddenField($element.'_day', substr($this->getAddElementStorage($element), 6, 2)) ?><?php echo $this->getTemplate()->Form()->drawHiddenField($element.'_month', substr($this->getAddElementStorage($element), 4, 2)) ?><?php echo $this->getTemplate()->Form()->drawHiddenField($element.'_year', substr($this->getAddElementStorage($element), 0, 4)) ?>

			<?php if ($this->getAddElementOption($element, 'month_asname')===true): ?><?php echo strftime(str_replace('%m.', ' %B ', $this->getAddElementOption($element, 'date_format')), mktime(12, 0, 0, substr($this->getAddElementStorage($element), 4, 2), substr($this->getAddElementStorage($element), 6, 2), substr($this->getAddElementStorage($element), 0, 4))) ?><?php else: ?><?php echo strftime($this->getAddElementOption($element, 'date_format'), mktime(12, 0, 0, substr($this->getAddElementStorage($element), 4, 2), substr($this->getAddElementStorage($element), 6, 2), substr($this->getAddElementStorage($element), 0, 4))) ?><?php endif ?><?php endif ?>
	</div>

	<?php /* error */ ?>
	<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?>
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