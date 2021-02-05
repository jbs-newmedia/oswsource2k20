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

	<div class="form-group ddm_element_<?php echo $this->getEditElementValue($element, 'id') ?>">

		<?php /* label */ ?>
		<label for="<?php echo $element ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getEditElementValue($element, 'title')) ?><?php if ($this->getEditElementOption($element, 'required')===true): ?><?php echo $this->getGroupMessage('form_title_required_icon') ?><?php endif ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>

		<?php /* read only */ ?>
		<div class="form-control readonly">
			<?php if ($this->getEditElementOption($element, 'ishtml')===true): ?><?php echo $this->getEditElementOption($element, 'label') ?><?php else: ?><?php echo \osWFrame\Core\HTML::outputString($this->getEditElementOption($element, 'label')) ?><?php endif ?>
		</div>

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
