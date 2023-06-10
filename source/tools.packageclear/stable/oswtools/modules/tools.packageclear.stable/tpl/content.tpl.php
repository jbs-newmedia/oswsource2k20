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

	<a href="javascript:osWTools_selectAll('#oswtools_packageclear')" class="btn btn-secondary">Select all</a>
	<a href="javascript:osWTools_selectNone('#oswtools_packageclear')" class="btn btn-secondary">Select none</a>
	<a href="javascript:osWTools_selectInvert('#oswtools_packageclear')" class="btn btn-secondary">Invert selection</a>

	<hr/>

	<?php echo $osW_Form->startForm('oswtools_packageclear_form', 'current', '', ['input_addid'=>true]); ?>

	<table id="oswtools_packageclear" class="table table-striped table-bordered">
		<thead>
		<tr>
			<th class="text-center pt-1">Status</th>
			<th class="mt-1">Name</th>
			<th class="text-center">Changelog</th>
			<th class="text-center">Configure</th>
			<th class="text-center">Filelist</th>
			<th class="text-center">Package</th>
		</tr>
		</thead>
		<tbody>

		<?php if ($Tool->getList()!=[]): ?>

			<?php foreach ($Tool->getList() as $element=>$status): ?>
				<tr>
					<td class="text-center">
						<?php echo $osW_Form->drawCheckboxField('package['.$element.']', 1, 0); ?>
					</td>
					</td>
					<td><?php echo $element ?></td>
					<td class="text-center"><?php if ($status['changelog']===true):?>X<?php else:?>-<?php endif?></td>
					<td class="text-center"><?php if ($status['configure']===true):?>X<?php else:?>-<?php endif?></td>
					<td class="text-center"><?php if ($status['filelist']===true):?>X<?php else:?>-<?php endif?></td>
					<td class="text-center"><?php if ($status['package']===true):?>X<?php else:?>-<?php endif?></td>
				</tr>
			<?php endforeach ?>

		<?php else: ?>

			<tr>
				<td colspan="6">Nothing to do.</td>
			</tr>

		<?php endif ?>

		</tbody>
	</table>

	<?php if ($Tool->getList()!=[]): ?>

		<hr/>

		<a href="javascript:$('#oswtools_packageclear_form').submit()" class="btn btn-primary d-block">Remove selected packages</a>

		<?php echo $osW_Form->drawHiddenField('doaction', 'doclear'); ?><?php endif ?>

	<?php echo $osW_Form->endForm(); ?>


<?php endif ?>