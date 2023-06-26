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

<?php if (in_array(\osWFrame\Core\Settings::getAction(), ['about'])): ?>

	<?php include \osWFrame\Core\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'tpl'.DIRECTORY_SEPARATOR.'about.tpl.php'; ?>

<?php elseif (in_array(\osWFrame\Core\Settings::getAction(), ['changelog'])): ?>

	<?php include \osWFrame\Core\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'tpl'.DIRECTORY_SEPARATOR.'changelog.tpl.php'; ?>

<?php else: ?>

	<a href="javascript:osWTools_selectAll('#oswtools_resourceclear')" class="btn btn-secondary">Select all</a>
	<a href="javascript:osWTools_selectNone('#oswtools_resourceclear')" class="btn btn-secondary">Select none</a>
	<a href="javascript:osWTools_selectInvert('#oswtools_resourceclear')" class="btn btn-secondary">Invert selection</a>

	<br/><br/>

	<p>Please select your resource you want to clear and confirm your input by pressing the button "Clear selected directories from resource".</p>

	<hr/>


	<?php echo $osW_Form->startForm('oswtools_resourceclear_form', 'current', '', ['input_addid'=>true]); ?>

	<table id="oswtools_resourceclear" class="table table-striped table-bordered">
		<thead>
		<tr>
			<th style="width:3rem;" class="text-center">Clear</th>
			<th>Directory</th>
		</tr>
		</thead>
		<tbody>
		<?php if (count($Tool->getResourceList())>0): ?><?php $i=0;
			foreach ($Tool->getResourceList() as $dir):$i++; ?>
				<tr>
					<td class="text-center"><?php echo $osW_Form->drawCheckboxField('dir['.$dir.']', 1, 0); ?></td>
					<td><?php echo $dir ?></td>
				</tr>
			<?php endforeach ?><?php endif ?>
		</tbody>
	</table>


	<?php if (count($Tool->getResourceList())>0): ?>

		<hr/>

		<a href="javascript:$('#oswtools_resourceclear_form').submit()" class="btn btn-primary d-block">Clear selected directories from resource</a>

		<?php echo $osW_Form->drawHiddenField('doaction', 'doclear'); ?><?php endif ?>

	<?php echo $osW_Form->endForm(); ?>


<?php endif ?>