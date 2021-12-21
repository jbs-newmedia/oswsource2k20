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
	<label class="form-label" for="<?php echo $element ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getSendElementValue($element, 'title')) ?><?php if ($this->getSendElementOption($element, 'required')===true): ?><?php echo $this->getGroupMessage('form_title_required_icon') ?><?php endif ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>

	<?php if ($this->getSendElementOption($element, 'read_only')===true): ?>

		<?php /* read only */ ?>

		<?php $bitmask=$this->getSendElementStorage($element); ?>

		<?php if ($this->getSendElementOption($element, 'orientation')=='horizontal'): ?><div><?php endif ?>

		<?php foreach ($this->getSendElementOption($element, 'data') as $key=>$value): ?><?php if ($this->getSendElementOption($element, 'orientation')=='horizontal'): ?><div class="form-check-inline"><?php endif ?>
			<div class="custom-checkbox">
				<?php if (isset($bitmask[$key])&&($bitmask[$key]=='1')): ?><?php echo $this->getGroupMessage('log_char_true').' '.\osWFrame\Core\HTML::outputString($value) ?>1
				<?php else: ?><?php echo $this->getGroupMessage('log_char_false').' '.\osWFrame\Core\HTML::outputString($value) ?>0
				<?php endif ?>
				<?php echo $this->getTemplate()->Form()->drawHiddenField($element.'_'.$key, (isset($bitmask[$key])?1:0)) ?>
			</div>
			<?php if ($this->getSendElementOption($element, 'orientation')=='horizontal'): ?></div><?php endif ?><?php endforeach ?><?php if ($this->getSendElementOption($element, 'orientation')=='horizontal'): ?></div><?php endif ?>

	<?php else: ?>

		<?php /* input */ ?><?php $bitmask=$this->getSendElementStorage($element); ?><?php if ($this->getSendElementOption($element, 'orientation')=='horizontal'): ?><div><?php endif ?>

		<?php foreach ($this->getSendElementOption($element, 'data') as $key=>$value): ?><?php if ($this->getSendElementOption($element, 'orientation')=='horizontal'): ?><div class="form-check-inline"><?php endif; ?>
			<div class="form-check">
				<?php echo $this->getTemplate()->Form()->drawCheckBoxField($element.'_'.$key, '1', ((isset($bitmask[$key])&&($bitmask[$key]=='1'))?1:0), ['input_parameter'=>'title="'.\osWFrame\Core\HTML::outputString($value).'"', 'input_class'=>'form-check-input']) ?>
				<label class="form-check-label<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?> text-danger<?php endif ?>" for="<?php echo $element.'_'.$key ?>0"><?php echo \osWFrame\Core\HTML::outputString($value) ?></label>
			</div>
			<?php if ($this->getSendElementOption($element, 'orientation')=='horizontal'): ?></div><?php endif ?><?php endforeach ?><?php if ($this->getSendElementOption($element, 'orientation')=='horizontal'): ?></div><?php endif ?>

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