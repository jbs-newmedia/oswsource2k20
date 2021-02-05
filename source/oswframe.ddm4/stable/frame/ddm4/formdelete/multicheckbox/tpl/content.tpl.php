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
	<label for="<?php echo $element ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getDeleteElementValue($element, 'title')) ?><?php if ($this->getDeleteElementOption($element, 'required')===true): ?><?php echo $this->getGroupMessage('form_title_required_icon') ?><?php endif ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>

	<?php /* read only */ ?>
	<?php $multicheckbox=[] ?>
	<?php if (strlen($this->getDeleteElementStorage($element))>0): ?><?php $multicheckbox=explode($this->getDeleteElementOption($element, 'separator'), $this->getDeleteElementStorage($element)) ?><?php endif ?>
	<?php if ($this->getDeleteElementOption($element, 'orientation')=='horizontal'): ?>
	<div><?php endif ?>
		<?php foreach ($this->getDeleteElementOption($element, 'data') as $key=>$value): ?><?php if ($this->getDeleteElementOption($element, 'orientation')=='horizontal'): ?><div class="form-check-inline"><?php endif ?>
			<div class="custom-checkbox">
				<?php if (in_array($key, $multicheckbox)): ?><?php echo '#1# '.\osWFrame\Core\HTML::outputString($value) ?><?php else: ?><?php echo '#0# '.\osWFrame\Core\HTML::outputString($value) ?><?php endif ?><?php echo $this->getTemplate()->Form()->drawHiddenField($element.'_'.$key, (isset($bitmask[$key])?1:0)) ?>
			</div>
			<?php if ($this->getDeleteElementOption($element, 'orientation')=='horizontal'): ?></div><?php endif ?><?php endforeach ?>
		<?php if ($this->getDeleteElementOption($element, 'orientation')=='horizontal'): ?></div><?php endif ?>

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