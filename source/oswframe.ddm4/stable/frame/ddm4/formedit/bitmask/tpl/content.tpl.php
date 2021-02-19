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

	<?php if ($this->getEditElementOption($element, 'read_only')===true): ?>

		<?php /* read only */ ?><?php $bitmask=$this->getEditElementStorage($element); ?><?php if ($this->getEditElementOption($element, 'orientation')=='horizontal'): ?><div><?php endif ?>

		<?php foreach ($this->getEditElementOption($element, 'data') as $key=>$value): ?><?php if ($this->getEditElementOption($element, 'orientation')=='horizontal'): ?><div class="form-check-inline"><?php endif ?>
			<div class="custom-checkbox">
				<?php if (isset($bitmask[$key])&&($bitmask[$key]=='1')): ?><?php echo $this->getGroupMessage('log_char_true').' '.\osWFrame\Core\HTML::outputString($value) ?>1
				<?php else: ?><?php echo $this->getGroupMessage('log_char_false').' '.\osWFrame\Core\HTML::outputString($value) ?>0
				<?php endif ?>
				<?php echo $this->getTemplate()->Form()->drawHiddenField($element.'_'.$key, (isset($bitmask[$key])?1:0)) ?>
			</div>
			<?php if ($this->getEditElementOption($element, 'orientation')=='horizontal'): ?></div><?php endif ?><?php endforeach ?><?php if ($this->getEditElementOption($element, 'orientation')=='horizontal'): ?></div><?php endif ?>

	<?php else: ?>

		<?php /* input */ ?><?php $bitmask=$this->getEditElementStorage($element); ?><?php if ($this->getEditElementOption($element, 'orientation')=='horizontal'): ?><div><?php endif ?>

		<?php foreach ($this->getEditElementOption($element, 'data') as $key=>$value): ?><?php if ($this->getEditElementOption($element, 'orientation')=='horizontal'): ?><div class="form-check-inline"><?php endif; ?>
			<div class="custom-control custom-checkbox">
				<?php echo $this->getTemplate()->Form()->drawCheckBoxField($element.'_'.$key, '1', ((isset($bitmask[$key])&&($bitmask[$key]=='1'))?1:0), ['input_parameter'=>'title="'.\osWFrame\Core\HTML::outputString($value).'"', 'input_class'=>'custom-control-input']) ?>
				<label class="custom-control-label<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?> text-danger<?php endif ?>" for="<?php echo $element.'_'.$key ?>0"><?php echo \osWFrame\Core\HTML::outputString($value) ?></label>
			</div>
			<?php if ($this->getEditElementOption($element, 'orientation')=='horizontal'): ?></div><?php endif ?><?php endforeach ?><?php if ($this->getEditElementOption($element, 'orientation')=='horizontal'): ?></div><?php endif ?>

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