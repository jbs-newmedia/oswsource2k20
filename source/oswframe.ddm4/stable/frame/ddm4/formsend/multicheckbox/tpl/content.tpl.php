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
			<?php $multicheckbox=[] ?>
			<?php if (strlen($this->getSendElementStorage($element))>0): ?><?php $multicheckbox=explode($this->getSendElementOption($element, 'separator'), $this->getSendElementStorage($element)) ?><?php endif ?>
			<ul class="table_ddm_list <?php if ($this->getSendElementOption($element, 'orientation')=='horizontal'): ?><?php echo ' table_ddm_list_horizontal' ?><?php else: ?><?php echo 'table_ddm_list_vertical' ?><?php endif ?>">
				<?php foreach ($this->getSendElementOption($element, 'data') as $key=>$value): ?>
					<li>
						<?php if (in_array($key, $multicheckbox)): ?><?php echo $this->getTemplate()->Form()->drawCheckboxField($element.'_'.$key, '1', 1) ?><?php echo \osWFrame\Core\HTML::outputString($value) ?><?php else: ?><?php echo $this->getTemplate()->Form()->drawCheckboxField($element.'_'.$key, '1', 0) ?><?php echo \osWFrame\Core\HTML::outputString($value) ?><?php endif ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</td>
	</tr>

<?php if ($this->getSendElementOption($element, 'notice')!=''): ?>
	<tr class="table_ddm_row table_ddm_row_data <?php if ($this->getTemplate()->Form()->getErrorMessage($element)===true): ?>table_ddm_row_error<?php endif ?> ddm_element_<?php echo $this->getSendElementValue($element, 'id') ?> ">
		<td class="table_ddm_col table_ddm_col_data table_ddm_col_notice">
			<?php echo \osWFrame\Core\HTML::outputString($this->getSendElementOption($element, 'notice')) ?>
		</td>
	</tr>
<?php endif ?>