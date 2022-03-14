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

	<p>Please enter the requested url from your serverlist and confirm your input by pressing the button "Install serverlist". The current list of available servers is available at <a href="https://oswframe.com/serverlist" target="_blank">oswframe.com/serverlist</a>.</p>

	<hr/>

	<?php echo $osW_Form->startForm('oswtools_installserverlist_form', 'current', '', ['input_addid'=>true]); ?>

	<label class="font-weight-bold" for="conf_url">Serverlist*:</label>
	<div class="input-group mb-3 has-validation">
		<span class="input-group-text"><i class="fas fa-link fa-fw"></i></span>
		<?php echo $osW_Form->drawTextField('conf_url', '', ['input_class'=>'form-control', 'input_errorclass'=>'is-invalid']) ?>
		<div class="invalid-feedback"><?php echo $osW_Form->getErrorMessage('conf_url') ?></div>
	</div>

	<hr/>

	<a href="javascript:$('#oswtools_installserverlist_form').submit()" class="btn btn-primary d-block">Install serverlist</a>

	<?php echo $osW_Form->drawHiddenField('doaction', 'doinstall'); ?>

	<?php echo $osW_Form->endForm(); ?>

<?php endif ?>