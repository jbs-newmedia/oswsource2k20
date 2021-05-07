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

<?php if (in_array(\osWFrame\Core\Settings::getAction(), ['about'])): ?>

	<?php include \osWFrame\Core\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'tpl'.DIRECTORY_SEPARATOR.'about.tpl.php'; ?>

<?php elseif (in_array(\osWFrame\Core\Settings::getAction(), ['changelog'])): ?>

	<?php include \osWFrame\Core\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'tpl'.DIRECTORY_SEPARATOR.'changelog.tpl.php'; ?>

<?php elseif (in_array(\osWFrame\Core\Settings::getAction(), ['settings'])): ?>

	<?php echo $osW_Form->startForm('oswtools_projectclear_form', 'current', '', ['input_addid'=>true]); ?>

	<label class="font-weight-bold" for="projectclear_dirs">Ignored directories:</label>
	<div class="input-group mb-3">
		<span class="input-group-text"><i class="fas fa-folder fa-fw"></i></span>
		<?php echo $osW_Form->drawTextareaField('projectclear_dirs', implode("\n", ($Tool->getArraySetting('projectclear_dirs')==null)?[]:$Tool->getArraySetting('projectclear_dirs')), ['input_class'=>'form-control', 'input_errorclass'=>'is-invalid', 'input_parameter'=>'rows="8"']) ?>
	</div>

	<label class="font-weight-bold" for="projectclear_files">Ignored files:</label>
	<div class="input-group mb-3">
		<span class="input-group-text"><i class="fas fa-file fa-fw"></i></span>
		<?php echo $osW_Form->drawTextareaField('projectclear_files', implode("\n", ($Tool->getArraySetting('projectclear_files')==null)?[]:$Tool->getArraySetting('projectclear_files')), ['input_class'=>'form-control', 'input_errorclass'=>'is-invalid', 'input_parameter'=>'rows="8"']) ?>
	</div>

	<hr/>

	<a href="javascript:$('#oswtools_projectclear_form').submit()" class="btn btn-primary d-block">Save settings</a>

	<?php echo $osW_Form->drawHiddenField('doaction', 'dosave'); ?>

	<?php echo $osW_Form->endForm(); ?>

<?php else: ?>

	<a href="javascript:osWTools_selectAll('#oswtools_projectclear')" class="btn btn-secondary">Select all</a>
	<a href="javascript:osWTools_selectNone('#oswtools_projectclear')" class="btn btn-secondary">Select none</a>
	<a href="javascript:osWTools_selectInvert('#oswtools_projectclear')" class="btn btn-secondary">Invert selection</a>

	<hr/>

	<?php echo $osW_Form->startForm('oswtools_projectclear_form', 'current', '', ['input_addid'=>true]);?>

	<table id="oswtools_projectclear" class="table table-striped table-bordered">
		<thead>
		<tr>
			<th class="text-center pt-1">Status</th>
			<th class="text-center mt-1">Type</th>
			<th>Directory/File</th>
			<th class="text-center">Options</th>
		</tr>
		</thead>
		<tbody>

		<?php if ($Tool->getList()!=[]): ?>

			<?php foreach ($Tool->getList() as $element=>$status): ?>
				<tr>
					<td class="text-center">
			<?php if ($status['t']=='f'): ?><?php echo $osW_Form->drawCheckboxField('file['.$element.']', 1, 0);?><?php endif ?>
			<?php if ($status['t']=='d'): ?><?php echo $osW_Form->drawCheckboxField('dir['.$element.']', 1, 0);?><?php endif ?>
				</td>
					</td>
					<td class="text-center"><span class="btn btn-xs" disabled><?php if ($status['t']=='f'): ?>
								<i class="fas fa-file fa-fw"></i><?php elseif ($status['t']='d'): ?>
								<i class="fas fa-folder fa-fw"></i><?php else: ?>
								<i class="fas fa-bug"></i><?php endif ?></span></td>
					<td><?php echo $element ?></td>
					<td class="projectclear_options text-center">
						<a title="Remove" href="javascript:engine('<?php echo $this->buildhrefLink('current', 'action=settings&doaction=doignore') ?>', '<?php echo $this->buildhrefLink('current', 'action=start') ?>', '<?php echo $element ?>', '<?php echo $status['t'] ?>')" class="btn btn-primary btn-xs"><i class="fa fa-times fa-fw"></i></a>
					</td>
				</tr>
			<?php endforeach ?>

		<?php else: ?>

			<tr>
				<td colspan="4">Nothing changed.</td>
			</tr>

		<?php endif ?>

		</tbody>
	</table>

	<?php if ($Tool->getList()!=[]): ?>

		<hr/>

		<a href="javascript:$('#oswtools_projectclear_form').submit()" class="btn btn-primary d-block">Clear selected files/directories from project</a>

		<?php echo $osW_Form->drawHiddenField('doaction', 'doclear');?>
	<?php endif?>

	<?php echo $osW_Form->endForm();?>


<?php endif ?>