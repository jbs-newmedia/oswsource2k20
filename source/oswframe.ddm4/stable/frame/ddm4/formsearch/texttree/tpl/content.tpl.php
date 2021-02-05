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
<div class="form-group<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?> has-error<?php endif ?> ddm_element_<?php echo $this->getSearchElementValue($element, 'id') ?>">
	<label for="<?php echo $element ?>" class="control-label"><?php echo \osWFrame\Core\HTML::outputString($this->getSearchElementValue($element, 'title')) ?><?php if ($this->getSearchElementOption($element, 'required')===true): ?><?php echo $this->getGroupMessage('form_title_required_icon') ?><?php endif ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>
	<?php echo $this->getTemplate()->Form()->drawTextField($element, $this->getSearchElementStorage($element), ['input_class'=>'form-control']); ?>
	<?php if ($this->getSearchElementOption($element, 'notice')!=''): ?>
		<span class="help-block"><?php echo \osWFrame\Core\HTML::outputString($this->getSearchElementOption($element, 'notice')) ?></span>
	<?php endif ?>
	<?php if ($this->getSearchElementOption($element, 'buttons')!=''): ?>
		<div class="button-group">
			<?php echo implode(' ', $this->getSearchElementOption($element, 'buttons')) ?>
		</div>
	<?php endif ?>
</div>