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

<?php else: ?>

	<?php echo $osW_Form->startForm('oswtools_configure_form', 'current', '', ['input_addid'=>true]); ?>

	<h3>Step <?php echo $Tool->getPage() ?>/<?php echo $Tool->getPages() ?>: <?php if ($Tool->isLastPage()===true): ?>Finished<?php else:?><?php echo \osWFrame\Core\HTML::outputString($Tool->getSettingAsString('page_title')) ?><?php endif?></h3>

	<hr/>

	<?php if (\osWFrame\Core\MessageStack::getMessages('configure')!=[]): ?>

		<?php foreach (\osWFrame\Core\MessageStack::getMessages('configure') as $type=>$messages): ?>

			<div class="alert alert-<?php echo $type ?>" role="alert">
				<?php foreach ($messages as $message): ?>

					<?php echo $message['msg'] ?><br/>

				<?php endforeach ?>

			</div>

			<hr/>
		<?php endforeach ?>

	<?php endif ?>

	<?php if ($Tool->getFields()!=[]): ?>

		<?php foreach ($Tool->getFields() as $config_element=>$config_data): ?>

			<?php $this->setVar('config_element', $config_element); ?><?php $this->setVar('config_data', $config_data); ?>

			<?php echo $this->fetchFileIfExists('configure'.DIRECTORY_SEPARATOR.$config_data['default_type'], '', 'resources'); ?>

		<?php endforeach ?>

		<hr/>
	<?php endif ?>

	<div class="row">

		<div class="col order-2 w-100">
			<?php if ($Tool->isLastPage()!==true): ?><?php echo $osW_Form->drawSubmit('next', 'Next step', ['input_class'=>'btn btn-primary w-100']); ?><?php endif ?>
		</div>
		<div class="col order-1 w-100">
			<?php if ($Tool->isFirstPage()!==true): ?><?php echo $osW_Form->drawSubmit('prev', 'Previous step', ['input_class'=>'btn btn-primary w-100']); ?><?php endif ?>
		</div>

	</div>

	<?php echo $osW_Form->drawHiddenField('page', $Tool->getPage()) ?>

	<?php echo $osW_Form->endForm(); ?>

<?php endif ?>