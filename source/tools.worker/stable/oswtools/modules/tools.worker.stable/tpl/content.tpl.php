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
 *
 */

?>

<?php if (in_array(\osWFrame\Core\Settings::getAction(), ['about'], true)): ?>

	<?php include \osWFrame\Core\Settings::getStringVar('settings_abspath') . 'resources' . \DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR . 'about.tpl.php'; ?>

<?php elseif (in_array(\osWFrame\Core\Settings::getAction(), ['changelog'], true)): ?>

	<?php include \osWFrame\Core\Settings::getStringVar('settings_abspath') . 'resources' . \DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR . 'changelog.tpl.php'; ?>

<?php else: ?>


	<?php echo $osW_Form->startForm('oswtools_worker_form', 'current', '', ['input_addid' => true]); ?>

	<label class="font-weight-bold" for="worker">Select worker:</label>
	<div class="input-group mb-3 has-validation">
		<?php echo $osW_Form->drawSelectField('worker', ['' => ''] + $Tool->getWorkerList(), '', ['input_class' => 'selectpicker form-control', 'input_errorclass' => 'is-invalid', 'input_parameter' => ' onchange="javascript:$(\'#oswtools_worker_form\').submit()" data-style="custom-select" data-size="10"']) ?>
		<div class="invalid-feedback"><?php echo $osW_Form->getErrorMessage('worker') ?></div>
	</div>

	<?php echo $osW_Form->drawHiddenField('doaction', 'doit'); ?>

	<?php echo $osW_Form->endForm(); ?>

	<?php if ($Tool->isWorker() === true): ?>

		<hr/>

	<?php include \osWFrame\Core\Settings::getStringVar('settings_abspath') . 'resources' . \DIRECTORY_SEPARATOR . 'php' . \DIRECTORY_SEPARATOR . 'worker' . \DIRECTORY_SEPARATOR . $Tool->getWorker() ?>

	<?php endif ?>

<?php endif ?>
