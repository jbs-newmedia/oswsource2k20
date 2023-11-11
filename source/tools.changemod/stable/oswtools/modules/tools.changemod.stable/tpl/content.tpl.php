<?php declare(strict_types=0);

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license MIT License
 *
 * @var \osWFrame\Core\Form $osW_Form
 * @var \osWFrame\Tools\Tool\Worker $Tool
 */

?>

<?php if (in_array(\osWFrame\Core\Settings::getAction(), ['about'], true)): ?>

	<?php include \osWFrame\Core\Settings::getStringVar('settings_abspath') . 'resources' . \DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR . 'about.tpl.php'; ?>

<?php elseif (in_array(\osWFrame\Core\Settings::getAction(), ['changelog'], true)): ?>

	<?php include \osWFrame\Core\Settings::getStringVar('settings_abspath') . 'resources' . \DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR . 'changelog.tpl.php'; ?>

<?php else: ?>

	<p>Please enter the requested data and confirm your input by pressing the button "Change access permissions".</p>

	<hr/>

	<?php echo $osW_Form->startForm('oswtools_chmod_form', 'current', '', ['input_addid' => true]); ?>

	<label class="font-weight-bold" for="main_username">Directory:</label>
	<div class="input-group mb-3 has-validation">
		<span class="input-group-text"><i class="fas fa-sitemap fa-fw"></i></span>
		<?php echo $osW_Form->drawSelectField('chmod_directory', ['' => ''] + $Tool->getDirList(), '', ['input_class' => 'selectpicker form-control', 'input_errorclass' => 'is-invalid', 'input_parameter' => ' data-style="form-select custom-select" data-size="10"']) ?>
		<div class="invalid-feedback"><?php echo $osW_Form->getErrorMessage('chmod_directory') ?></div>
	</div>

	<label class="font-weight-bold" for="chmod_files_select">Files (mode):</label>
	<div class="input-group mb-3 has-validation">
		<span class="input-group-text"><i class="fas fa-file fa-fw"></i></span>
		<?php echo $osW_Form->drawSelectField('chmod_files_select', $Tool->getFileOptions(), $Tool->getFile(), ['input_class' => 'selectpicker form-control', 'input_errorclass' => 'is-invalid', 'input_parameter' => ' data-style="form-select custom-select"']) ?>
		<div class="invalid-feedback"><?php echo $osW_Form->getErrorMessage('chmod_files_select') ?></div>
	</div>

	<label class="font-weight-bold" for="chmod_directory_select">Directories (mode):</label>
	<div class="input-group mb-3 has-validation">
		<span class="input-group-text"><i class="fas fa-folder fa-fw"></i></span>
		<?php echo $osW_Form->drawSelectField('chmod_directory_select', $Tool->getDirOptions(), $Tool->getDir(), ['input_class' => 'selectpicker form-control', 'input_errorclass' => 'is-invalid', 'input_parameter' => ' data-style="form-select custom-select"']) ?>
		<div class="invalid-feedback"><?php echo $osW_Form->getErrorMessage('chmod_directory_select') ?></div>
	</div>

	<?php if (count($Tool->getFileOptions()) > 0): ?>

		<hr/>

		<a href="javascript:$('#oswtools_chmod_form').submit()" class="btn btn-primary d-block">Change access permissions</a>

		<?php echo $osW_Form->drawHiddenField('doaction', 'dochange'); ?><?php endif ?>

	<?php echo $osW_Form->endForm(); ?>


<?php endif ?>
