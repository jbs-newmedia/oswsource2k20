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

<?php elseif (in_array(\osWFrame\Core\Settings::getAction(), ['protecttools'])): ?>


	<?php if (in_array($part, ['manage'])): ?>

	<?php endif ?>


<?php else: ?>

	<table id="oswtools_main" class="table table-striped table-bordered datatables">
		<thead>
		<tr>
			<th>Tool</th>
			<th>Release</th>
			<th>Information</th>
		</tr>
		</thead>
		<tbody>

		<?php if ($main_tools!=[]): ?>

			<?php foreach ($main_tools as $serverlist=>$tools): ?>

				<?php foreach ($tools as $package_name=>$package_data): ?>

					<tr>
						<td>
							<a href="<?php echo $this->buildhrefLink($package_data['package'].'.'.$package_data['release']) ?>"><?php if (isset($package_data['info']['name'])): ?><?php echo $package_data['info']['name']; ?><?php else: ?><?php echo $package_name; ?><?php endif ?></a>
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