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

	<ul class="nav nav-tabs mb-3">
		<?php foreach ($Tool->getList() as $serverlist=>$details): ?>
			<li class="nav-item">
				<a class="nav-link<?php if ($Tool->getSL()==$serverlist): ?> active"<?php endif ?>" href="<?php echo $this->buildhrefLink('current', 'sl='.$serverlist) ?>"><?php echo $details['info']['name'] ?></a>
			</li>
		<?php endforeach ?>
	</ul>

	<?php if ($Tool->getSL()==''):?>
		<script>var unsorted=5;</script>
	<?php elseif($Tool->getSL()=='custom'):?>
		<script>var unsorted=1;</script>
	<?php else:?>
		<script>var unsorted=4;</script>
	<?php endif?>

	<table id="oswtools_toolmanager" class="table table-striped table-bordered">
		<thead>
		<tr>
			<th>Tool</th>
			<th>Release</th>
			<th>Version (installed)</th>
			<th>Version (available)</th>
			<?php if ($Tool->getSL()==''): ?>
				<th>Serverlist</th>
			<?php endif ?>
			<th class="text-center">Options</th>
		</tr>
		</thead>
		<tbody>

		<?php if ($Tool->getTools()!=[]): ?>

			<?php foreach ($Tool->getTools() as $serverlist=>$tools): ?>

				<?php foreach ($tools as $package_name=>$package_data): ?>

					<?php if (($Tool->getSL()=='')||($serverlist==$Tool->getSL())): ?>

						<?php if ($serverlist=='custom'): ?>

							<tr id="package_<?php echo md5($serverlist.'#'.$package_data) ?>">
								<td><?php echo $package_data; ?></td>
								<td><?php if ((!isset($package_data['release']))||($package_data['release']=='0.0')): ?>-----<?php else: ?><?php echo $package_data['release']; ?><?php endif ?></td>
								<td class="manager_release"><?php if ((!isset($package_data['version_installed']))||($package_data['version_installed']=='0.0')): ?>-----<?php else: ?><?php echo $package_data['version_installed']; ?><?php endif ?></td>
								<td><?php if (!isset($package_data['version'])): ?>-----<?php else: ?><?php echo $package_data['version']; ?><?php endif ?></td>
								<?php if ($Tool->getSL()==''): ?>
									<td><?php echo $Tool->getList()[$serverlist]['info']['name'] ?></td>
								<?php endif?>
								<td class="manager_options text-center"></td>
							</tr>

						<?php else: ?>

							<tr id="package_<?php echo md5($serverlist.'#'.$package_data['package'].'#'.$package_data['release']) ?>">
								<td><?php echo $package_data['info']['name']; ?></td>
								<td><?php echo $package_data['release']; ?></td>
								<td class="manager_release"><?php if ($package_data['version_installed']=='0.0'): ?>-----<?php else: ?><?php echo $package_data['version_installed']; ?><?php endif ?></td>
								<td><?php echo $package_data['version']; ?></td>
								<?php if ($Tool->getSL()==''): ?>
									<td><?php echo $Tool->getList()[$serverlist]['info']['name'] ?></td>
								<?php endif?>
								<td class="manager_options text-center"><?php echo $Tool->outputOption($this->buildhrefLink('current'), md5($serverlist.'#'.$package_data['package'].'#'.$package_data['release']), $package_data, $serverlist);?></td>
							</tr>

						<?php endif ?>

					<?php endif ?>

				<?php endforeach ?>

			<?php endforeach ?>

		<?php endif ?>

		</tbody>
	</table>

	<a href="javascript:updateAll()" class="mt-3 btn btn-primary d-block">Update all tools</a>

<?php endif ?>