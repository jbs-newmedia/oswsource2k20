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

<div class="form-group<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?> has-error<?php endif ?> ddm_element_<?php echo $this->getSendElementValue($element, 'id') ?>">
	<label class="form-label" for="<?php echo $element ?>"><?php echo \osWFrame\Core\HTML::outputString($this->getSendElementValue($element, 'title')) ?><?php if ($this->getSendElementOption($element, 'required')===true): ?><?php echo $this->getGroupMessage('form_title_required_icon') ?><?php endif ?><?php echo $this->getGroupMessage('form_title_closer') ?></label>
	<?php if ($this->getSearchElementOption($element, 'ishtml')===true): ?>
		<div class="form-control readonly" style="height:auto;"><?php echo $this->getSendElementOption($element, 'label') ?></div>
	<?php else: ?>
		<div class="form-control readonly" style="height:auto;"><?php echo \osWFrame\Core\HTML::outputString($this->getSendElementOption($element, 'label')) ?></div>
	<?php endif ?>
	<?php if ($this->getTemplate()->Form()->getErrorMessage($element)): ?>
		<span class="help-block"><?php echo $this->getTemplate()->Form()->getErrorMessage($element) ?></span>
	<?php elseif ($this->getSendElementOption($element, 'notice')!=''): ?>
		<span class="help-block"><?php echo \osWFrame\Core\HTML::outputString($this->getSendElementOption($element, 'notice')) ?></span>
	<?php endif ?>
	<?php if ($this->getSendElementOption($element, 'buttons')!=''): ?>
		<div class="button-group">
			<?php echo implode(' ', $this->getSendElementOption($element, 'buttons')) ?>
		</div>
	<?php endif ?>
</div>