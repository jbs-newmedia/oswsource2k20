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

	<tr class="table_ddm_row table_ddm_row_data <?php echo osW_Template::getInstance()->getColorClass('table_ddm_rows', ['table_ddm_row_cella', 'table_ddm_row_cellb']) ?> <?php if ($this->getTemplate()->Form()->getErrorMessage($element)===true): ?>table_ddm_row_error<?php endif ?> ddm_element_<?php echo $this->getSendElementValue($element, 'id') ?> ">
		<td <?php if ($this->getSendElementOption($element, 'notice')!=''): ?>rowspan="2"<?php endif ?> class="table_ddm_col table_ddm_col_data table_ddm_col_title"><?php echo \osWFrame\Core\HTML::outputString($this->getSendElementValue($element, 'title')) ?><?php if ($this->getSendElementOption($element, 'required')===true): ?><?php echo $this->getGroupMessage('form_title_required_icon') ?><?php endif ?><?php echo $this->getGroupMessage('form_title_closer') ?></td>
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_form">
			<?php if ($this->getSendElementOption($element, 'read_only')===true): ?><?php if (($this->getSendElementStorage($element)=='')||($this->getSendElementStorage($element)=='00000000')): ?>
				---
			<?php else: ?><?php echo $this->getTemplate()->Form()->drawHiddenField($element.'_day', substr($this->getSendElementStorage($element), 6, 2)) ?><?php echo $this->getTemplate()->Form()->drawHiddenField($element.'_month', substr($this->getSendElementStorage($element), 4, 2)) ?><?php echo $this->getTemplate()->Form()->drawHiddenField($element.'_year', substr($this->getSendElementStorage($element), 0, 4)) ?>

				<?php if ($this->getSendElementOption($element, 'month_asname')===true): ?><?php echo strftime(str_replace('%m.', ' %B ', $this->getSendElementOption($element, 'date_format')), mktime(12, 0, 0, substr($this->getSendElementStorage($element), 4, 2), substr($this->getSendElementStorage($element), 6, 2), substr($this->getSendElementStorage($element), 0, 4))) ?><?php else: ?><?php echo strftime($this->getSendElementOption($element, 'date_format'), mktime(12, 0, 0, substr($this->getSendElementStorage($element), 4, 2), substr($this->getSendElementStorage($element), 6, 2), substr($this->getSendElementStorage($element), 0, 4))) ?><?php endif ?><?php endif ?><?php else: ?><?php $data=$this->getSendElementOption($element, 'data') ?>

				<?php $date_format=$this->getSendElementOption($element, 'date_format') ?><?php $date_format=str_replace('%', '%osw_tmp_ddm3_%', $date_format) ?>

				<?php $date_format=str_replace('%osw_tmp_ddm3_%d', $this->getTemplate()->Form()->drawSelectField($element.'_day', $data['day'], substr($this->getSendElementStorage($element), 6, 2)), $date_format) ?><?php $date_format=str_replace('%osw_tmp_ddm3_%m', $this->getTemplate()->Form()->drawSelectField($element.'_month', $data['month'], substr($this->getSendElementStorage($element), 4, 2)), $date_format) ?><?php $date_format=str_replace('%osw_tmp_ddm3_%Y', $this->getTemplate()->Form()->drawSelectField($element.'_year', $data['year'], substr($this->getSendElementStorage($element), 0, 4)), $date_format) ?><?php $date_format=str_replace('%osw_tmp_ddm3_%y', $this->getTemplate()->Form()->drawSelectField($element.'_year', $data['year'], substr($this->getSendElementStorage($element), 0, 4)), $date_format) ?>

				<?php echo $date_format; ?><?php endif ?>
		</td>
	</tr>

<?php if ($this->getSendElementOption($element, 'notice')!=''): ?>
	<tr class="table_ddm_row table_ddm_row_data <?php if ($this->getTemplate()->Form()->getErrorMessage($element)===true): ?>table_ddm_row_error<?php endif ?> ddm_element_<?php echo $this->getSendElementValue($element, 'id') ?> ">
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_notice">
			<?php echo \osWFrame\Core\HTML::outputString($this->getSendElementOption($element, 'notice')) ?>
		</td>
	</tr>
<?php endif ?>