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
	<label for="<?php echo $element ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getSearchElementValue($element, 'title')) ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>

	<?php if ($this->getSearchElementOption($element, 'read_only')===true): ?>

		<?php /* read only */ ?><?php if ($this->getSearchElementOption($element, 'blank_value')===true): ?><?php $data=[''=>'']+$this->getSearchElementOption($element, 'data'); ?><?php else: ?><?php $data=$this->getSearchElementOption($element, 'data'); ?><?php endif ?><?php if (isset($data[$this->getSearchElementStorage($element)])): ?>
			<div class="form-control readonly"><?php echo \osWFrame\Core\HTML::outputString($data[$this->getSearchElementStorage($element)]) ?></div>
		<?php else: ?>
			<div class="form-control readonly">&nbsp;</div>
		<?php endif ?><?php echo $this->getTemplate()->Form()->drawHiddenField($element, $this->getSearchElementStorage($element)) ?>

	<?php else: ?>

		<?php /* input */ ?><?php if ($this->getSearchElementOption($element, 'blank_value')===true): ?><?php echo $this->getTemplate()->Form()->drawSelectField($element, ['%'=>$values['options']['text_all']]+[''=>' ']+$this->getSearchElementOption($element, 'data'), $this->getSearchElementStorage($element), ['input_class'=>'selectpicker form-control', 'input_errorclass'=>'is-invalid', 'input_parameter'=>' data-style="btn btn-outline-default"']) ?><?php else: ?><?php echo $this->getTemplate()->Form()->drawSelectField($element, ['%'=>$values['options']['text_all']]+$this->getSearchElementOption($element, 'data'), $this->getSearchElementStorage($element), ['input_class'=>'selectpicker form-control', 'input_errorclass'=>'is-invalid', 'input_parameter'=>' data-style="btn btn-outline-default"']) ?><?php endif ?>

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