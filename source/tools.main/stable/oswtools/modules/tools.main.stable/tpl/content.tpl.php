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

<?php elseif (in_array(\osWFrame\Core\Settings::getAction(), ['protecttools'])): ?>

	<ul class="nav nav-tabs mb-3">
		<li class="nav-item">
			<a class="nav-link<?php if (in_array(\osWFrame\Tools\Helper::getDoAction(), ['manage'])): ?> active<?php endif ?>" href="<?php echo $this->buildhrefLink('current', 'doaction=manage') ?>">Manage</a>
		</li>
		<li class="nav-item">
			<a class="nav-link<?php if (in_array(\osWFrame\Tools\Helper::getDoAction(), ['new'])): ?> active<?php endif ?>" href="<?php echo $this->buildhrefLink('current', 'doaction=new') ?>">New user</a>
		</li>
	</ul>

	<?php echo $osW_Form->startForm('oswtools_main_form', 'current', 'action=protecttools', ['input_addid'=>true]); ?>

	<?php if (in_array(\osWFrame\Tools\Helper::getDoAction(), ['new'])): ?>

		<p>Please enter the requested data and confirm your input by pressing the button "create user in .htaccess".</p>

		<hr/>

		<label class="font-weight-bold" for="main_username">Username*:</label>
		<div class="input-group mb-3 has-validation">
			<span class="input-group-text"><i class="fas fa-user fa-fw"></i></span>
			<?php echo $osW_Form->drawTextField('main_username', '', ['input_class'=>'form-control', 'input_errorclass'=>'is-invalid']) ?>
			<div class="invalid-feedback"><?php echo $osW_Form->getErrorMessage('main_username') ?></div>
		</div>

		<label class="font-weight-bold" for="main_password">Password*:</label>
		<div class="input-group mb-3 has-validation">
			<span class="input-group-text"><i class="fas fa-lock fa-fw"></i></span>
			<?php echo $osW_Form->drawPasswordField('main_password', '', ['input_class'=>'form-control', 'input_errorclass'=>'is-invalid']) ?>
			<div class="invalid-feedback"><?php echo $osW_Form->getErrorMessage('main_password') ?></div>
		</div>

		<label class="font-weight-bold" for="main_confirm_password">Password (confirm)*:</label>
		<div class="input-group mb-3 has-validation">
			<span class="input-group-text"><i class="fas fa-lock fa-fw"></i></span>
			<?php echo $osW_Form->drawPasswordField('main_confirm_password', '', ['input_class'=>'form-control', 'input_errorclass'=>'is-invalid']) ?>
			<div class="invalid-feedback"><?php echo $osW_Form->getErrorMessage('main_confirm_password') ?></div>
		</div>

		<hr/>

		<a href="javascript:$('#oswtools_main_form').submit()" class="btn btn-primary d-block">create user in .htaccess</a>

		<?php echo $osW_Form->drawHiddenField('doaction', 'donew'); ?>

	<?php endif ?>


	<?php if (in_array(\osWFrame\Tools\Helper::getDoAction(), ['manage'])): ?>

		<p>The user list provides an overview of all created users. Select "remove" and then the "remove selected users from .htaccess" button to delete the users.</p>

		<hr/>

		<table id="oswtools_main_protecttools_manager" class="table table-striped table-bordered">
			<thead>
			<tr>
				<th style="width:3rem;" class="text-center">Remove</th>
				<th>Username</th>
			</tr>
			</thead>
			<tbody>
			<?php if ($Tool->getHTUsers()!==[]): ?>

				<?php foreach ($Tool->getHTUsers() as $user=>$blank): ?>

					<tr>
						<td class="text-center"><?php echo $osW_Form->drawCheckboxField('updtusers['.$user.']', 1, 0); ?></td>
						<td><?php echo \osWFrame\Core\HTML::outputString($user) ?></td>
					</tr>

				<?php endforeach ?>

			<?php else: ?>
				<tr>
					<td colspan="2">No users available in table</td>
				</tr>
			<?php endif ?>
			</tbody>
		</table>

		<?php if ($Tool->getHTUsers()!==[]): ?>

			<hr/>

			<a href="javascript:$('#oswtools_main_form').submit()" class="btn btn-primary d-block">Remove selected users from .htaccess</a>


		<?php endif ?>


		<?php echo $osW_Form->drawHiddenField('doaction', 'domanage'); ?>

	<?php endif ?>

	<?php echo $osW_Form->endForm(); ?>

<?php elseif (in_array(\osWFrame\Core\Settings::getAction(), ['framekey'])): ?>


	<p>The Frame-Key is relevant for license management. If you already have one, please enter it.</p>

	<hr/>

	<?php echo $osW_Form->startForm('oswtools_framekey_form', 'current', '', ['input_addid'=>true]); ?>

	<label class="font-weight-bold" for="frame_key">Frame-Key:</label>
	<div class="input-group mb-3 has-validation">
		<span class="input-group-text"><i class="fas fa-key fa-fw"></i></span>
		<?php echo $osW_Form->drawTextField('frame_key', \osWFrame\Tools\Server::getFrameKey(), ['input_class'=>'form-control', 'input_errorclass'=>'is-invalid']) ?>
		<div class="invalid-feedback"><?php echo $osW_Form->getErrorMessage('frame_key') ?></div>
	</div>

	<label class="font-weight-bold" for="account_email">Account-Email:</label>
	<div class="input-group mb-3 has-validation">
		<span class="input-group-text"><i class="fas fa-at fa-fw"></i></span>
		<?php echo $osW_Form->drawTextField('account_email', \osWFrame\Tools\Server::getAccountEMail(), ['input_class'=>'form-control', 'input_errorclass'=>'is-invalid']) ?>
		<div class="invalid-feedback"><?php echo $osW_Form->getErrorMessage('account_email') ?></div>
	</div>

	<hr/>

	<a href="javascript:$('#oswtools_framekey_form').submit()" class="btn btn-primary d-block">Change Frame-Key</a>

	<?php echo $osW_Form->drawHiddenField('doaction', 'dochange'); ?>

	<?php echo $osW_Form->endForm(); ?>

	<hr>

	<a href="<?php echo $this->buildHrefLink('current', 'action=framekey&doaction=donew') ?>" class="float-right text-danger">Create new Frame-Key</a>

<?php else: ?>

	<table id="oswtools_main" class="table table-striped table-bordered">
		<thead>
		<tr>
			<th>Tool</th>
			<th>Release</th>
			<th>Information</th>
		</tr>
		</thead>
		<tbody>

		<?php if ($Tool->getTools()!=[]): ?>

			<?php foreach ($Tool->getTools() as $serverlist=>$tools): ?>

				<?php foreach ($tools as $package_name=>$package_data): ?>

					<tr>
						<td>
							<?php if (is_array($package_data)): ?>
								<a href="<?php echo $this->buildhrefLink($package_data['package'].'.'.$package_data['release']) ?>"><?php echo $package_data['info']['name']; ?></a>
							<?php else: ?>
								<a href="<?php echo $this->buildhrefLink($package_name) ?>"><?php echo $package_name; ?></a>
							<?php endif ?>
						</td>
						<td><?php if ($serverlist!='custom'): ?><?php echo $package_data['release'] ?><?php else: ?>custom<?php endif ?></td>
						<td>-----</td>
					</tr>

				<?php endforeach ?>

			<?php endforeach ?>

		<?php endif ?>

		</tbody>
	</table>

<?php endif ?>