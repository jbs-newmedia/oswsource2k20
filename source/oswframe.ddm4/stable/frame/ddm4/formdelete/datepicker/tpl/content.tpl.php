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

<div class="form-group ddm_element_<?php echo $this->getDeleteElementValue($element, 'id') ?>">

	<?php /* label */ ?>
	<label class="form-label" for="<?php echo $element ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getDeleteElementValue($element, 'title')) ?><?php if ($this->getDeleteElementOption($element, 'required')===true): ?><?php echo $this->getGroupMessage('form_title_required_icon') ?><?php endif ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>

	<?php /* read only */ ?>
	<div class="form-control readonly">
		<?php if (($this->getDeleteElementStorage($element)=='')||($this->getDeleteElementStorage($element)=='0')||($this->getDeleteElementStorage($element)=='00000000')||($this->getDeleteElementStorage($element)=='0')): ?>
			---
		<?php else: ?><?php echo $this->getTemplate()->Form()->drawHiddenField($element, strftime($this->getDeleteElementOption($element, 'format'), mktime(12, 0, 0, substr($this->getDeleteElementStorage($element), 4, 2), substr($this->getDeleteElementStorage($element), 6, 2), substr($this->getDeleteElementStorage($element), 0, 4)))) ?><?php if ($this->getDeleteElementOption($element, 'month_asname')===true): ?><?php echo strftime(str_replace('%m.', ' %B ', $this->getDeleteElementOption($element, 'format')), mktime(12, 0, 0, substr($this->getDeleteElementStorage($element), 4, 2), substr($this->getDeleteElementStorage($element), 6, 2), substr($this->getDeleteElementStorage($element), 0, 4))) ?><?php echo $this->getTemplate()->Form()->drawHiddenField($element, strftime(str_replace('%m.', ' %B ', $this->getDeleteElementOption($element, 'format')), mktime(12, 0, 0, substr($this->getDeleteElementStorage($element), 4, 2), substr($this->getDeleteElementStorage($element), 6, 2), substr($this->getDeleteElementStorage($element), 0, 4)))) ?><?php else: ?><?php echo strftime($this->getDeleteElementOption($element, 'format'), mktime(12, 0, 0, substr($this->getDeleteElementStorage($element), 4, 2), substr($this->getDeleteElementStorage($element), 6, 2), substr($this->getDeleteElementStorage($element), 0, 4))) ?><?php echo $this->getTemplate()->Form()->drawHiddenField($element, strftime($this->getDeleteElementOption($element, 'format'), mktime(12, 0, 0, substr($this->getDeleteElementStorage($element), 4, 2), substr($this->getDeleteElementStorage($element), 6, 2), substr($this->getDeleteElementStorage($element), 0, 4)))) ?><?php endif ?><?php endif ?>
	</div>

	<?php /* error */ ?>
	<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?>
		<div class="text-danger small"><?php echo $this->getTemplate()->Form()->getErrorMessage($element) ?></div>
	<?php endif ?>

	<?php /* notice */ ?>
	<?php if ($this->getDeleteElementOption($element, 'notice')!=''): ?>
		<div class="text-info"><?php echo \osWFrame\Core\HTML::outputString($this->getDeleteElementOption($element, 'notice')) ?></div>
	<?php endif ?>

	<?php /* buttons */ ?>
	<?php if ($this->getDeleteElementOption($element, 'buttons')!=''): ?>
		<div>
			<?php echo implode(' ', $this->getDeleteElementOption($element, 'buttons')) ?>
		</div>
	<?php endif ?>

</div>