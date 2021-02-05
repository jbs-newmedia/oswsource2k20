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

<div class="form-group ddm_element_<?php echo $this->getSearchElementValue($element, 'id') ?>">

	<?php /* label */ ?>
	<label><?php echo \osWFrame\Core\HTML::outputString($this->getSearchElementValue($element, 'title')) ?><?php echo $this->getGroupMessage('form_title_closer') ?></label><br/>

	<?php if ($this->getSearchElementOption($element, 'read_only')===true): ?>

		<?php /* read only */ ?><?php if ($this->getSearchElementStorage($element)==1): ?>
			<div class="form-control readonly"><?php echo \osWFrame\Core\HTML::outputString($values['options']['text_yes']) ?></div>
		<?php else: ?>
			<div class="form-control readonly"><?php echo \osWFrame\Core\HTML::outputString($values['options']['text_no']) ?></div>
		<?php endif ?><?php echo $this->getTemplate()->Form()->drawHiddenField($element, $this->getSearchElementStorage($element)) ?>

	<?php else: ?>

		<?php /* input */ ?>
		<div class="form-check-inline">
			<label class="form-check-label">
				<div class="custom-control custom-radio">
					<?php echo $this->getTemplate()->Form()->drawRadioField($element, '%', $this->getSearchElementStorage($element), ['input_class'=>'custom-control-input']) ?>
					<label class="custom-control-label<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?> text-danger<?php endif ?>" for="<?php echo $element ?>1"><?php echo \osWFrame\Core\HTML::outputString($values['options']['text_all']) ?></label>
				</div>
			</label>
		</div>
		<?php foreach (str_split($this->getSearchElementOption($element, 'displayorder')) as $key): ?><?php if ($key=='y'): ?>
			<div class="form-check-inline">
				<label class="form-check-label">
					<div class="custom-control custom-radio">
						<?php echo $this->getTemplate()->Form()->drawRadioField($element, '1', $this->getSearchElementStorage($element), ['input_class'=>'custom-control-input']) ?>
						<label class="custom-control-label<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?> text-danger<?php endif ?>" for="<?php echo $element ?>0"><?php echo \osWFrame\Core\HTML::outputString($values['options']['text_yes']) ?></label>
					</div>
				</label>
			</div>
		<?php endif ?><?php if ($key=='n'): ?>
			<div class="form-check-inline">
				<label class="form-check-label">
					<div class="custom-control custom-radio">
						<?php echo $this->getTemplate()->Form()->drawRadioField($element, '0', $this->getSearchElementStorage($element), ['input_class'=>'custom-control-input']) ?>
						<label class="custom-control-label<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?> text-danger<?php endif ?>" for="<?php echo $element ?>1"><?php echo \osWFrame\Core\HTML::outputString($values['options']['text_no']) ?></label>
					</div>
				</label>
			</div>
		<?php endif ?><?php endforeach ?>

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