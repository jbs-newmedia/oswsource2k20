<?php

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license MIT License
 */

?>

<?php if ($this->getSearchElementValue($element, 'title')==''): ?>
	<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', ['table_ddm_row_cella', 'table_ddm_row_cellb']) ?> <?php if ($this->getTemplate()->Form()->getErrorMessage($element)===true): ?>table_ddm_row_error<?php endif ?> ddm_element_<?php echo $this->getSearchElementValue($element, 'id') ?> ">
		<td colspan="2" <?php if ($this->getSearchElementOption($element, 'notice')!=''): ?>rowspan="2"<?php endif ?> class="table_ddm_col table_ddm_col_data table_ddm_col_label">
			<?php if ($this->getSearchElementOption($element, 'ishtml')===true): ?><?php echo $this->getSearchElementOption($element, 'label') ?><?php else: ?><?php echo \osWFrame\Core\HTML::outputString($this->getSearchElementOption($element, 'label')) ?><?php endif ?>
		</td>
	</tr>

<?php else: ?>
	<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', ['table_ddm_row_cella', 'table_ddm_row_cellb']) ?> <?php if ($this->getTemplate()->Form()->getErrorMessage($element)===true): ?>table_ddm_row_error<?php endif ?> ddm_element_<?php echo $this->getSearchElementValue($element, 'id') ?> ">
		<td <?php if ($this->getSearchElementOption($element, 'notice')!=''): ?>rowspan="2"<?php endif ?> class="table_ddm_col table_ddm_col_data table_ddm_col_title"><?php echo \osWFrame\Core\HTML::outputString($this->getSearchElementValue($element, 'title')) ?><?php echo $this->getGroupMessage('form_title_closer') ?></td>
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_form">
			<?php if ($this->getSearchElementOption($element, 'ishtml')===true): ?><?php echo $this->getSearchElementOption($element, 'label') ?><?php else: ?><?php echo \osWFrame\Core\HTML::outputString($this->getSearchElementOption($element, 'label')) ?><?php endif ?>
		</td>
	</tr>

<?php endif ?>

<?php if ($this->getSearchElementOption($element, 'notice')!=''): ?>
	<tr class="table_ddm_row table_ddm_row_data <?php if ($this->getTemplate()->Form()->getErrorMessage($element)===true): ?>table_ddm_row_error<?php endif ?> ddm_element_<?php echo $this->getSearchElementValue($element, 'id') ?> ">
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_notice">
			<?php echo \osWFrame\Core\HTML::outputString($this->getSearchElementOption($element, 'notice')) ?>
		</td>
	</tr>
<?php endif ?>