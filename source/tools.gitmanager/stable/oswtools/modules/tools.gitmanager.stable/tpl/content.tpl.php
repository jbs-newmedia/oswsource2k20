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
 * @var \osWFrame\Tools\Tool\GITManager $Tool
 * @var \osWFrame\Core\Template $this
 *
 */

?>

<?php if (in_array(\osWFrame\Core\Settings::getAction(), ['about'], true)): ?>

	<?php include \osWFrame\Core\Settings::getStringVar('settings_abspath') . 'resources' . \DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR . 'about.tpl.php'; ?>

<?php elseif (in_array(\osWFrame\Core\Settings::getAction(), ['changelog'], true)): ?>

	<?php include \osWFrame\Core\Settings::getStringVar('settings_abspath') . 'resources' . \DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR . 'changelog.tpl.php'; ?>

<?php else: ?>

	<table id="oswtools_gitmanager" class="table table-striped table-bordered">
		<thead>
		<tr>
			<th>Package</th>
			<th>Source</th>
			<th>Release</th>
			<th>Version (installed)</th>
			<th>Version (available)</th>
			<th class="text-center">Options</th>
		</tr>
		</thead>
		<tbody>

		<?php if ($Tool->getPackages() !== []): ?>

			<?php foreach ($Tool->getPackages() as $package_name => $package_data): ?>

				<tr id="package_<?php echo $package_name ?>">
					<td><?php echo \osWFrame\Core\HTML::outputString($package_data['name']); ?></td>
					<td><?php echo \osWFrame\Core\HTML::outputString($package_data['git']); ?></td>
					<td><?php echo \osWFrame\Core\HTML::outputString($package_data['release']); ?></td>
					<td class="manager_release"><?php echo $package_data['installed']; ?></td>
					<td><?php echo $package_data['available']; ?></td>
					<td class="manager_options text-center"><?php echo $Tool->outputOption($this->buildhrefLink('current'), $package_name, $package_data); ?></td>
				</tr>

			<?php endforeach ?>

		<?php endif ?>

		</tbody>
	</table>

	<?php if ($Tool->getPackages() !== []): ?>
		<a href="javascript:updateAll()" class="mt-3 btn btn-primary d-block">Update all packages</a>
	<?php endif ?>

<?php endif ?>
