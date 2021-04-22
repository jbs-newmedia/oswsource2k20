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

	<?php foreach ($Tool->getLicenseList() as $key=>$details): ?>

		<table class="table table-striped table-bordered">
			<thead>
			<tr>
				<th colspan="2"><?php echo \osWFrame\Core\HTML::outputString($details['server_list']) ?></th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>Server-Name</td>
				<td><?php echo \osWFrame\Core\HTML::outputString($details['server_name']) ?></td>
			</tr>
			<tr>
				<td>Server-Addr</td>
				<td><?php echo $details['server_addr'] ?></td>
			</tr>
			<tr>
				<td>Frame-Key</td>
				<td><code><?php echo $details['frame_key'] ?></code></td>
			</tr>
			<tr>
				<td>License-Key</td>
				<td><code><?php echo $details['licensekey'] ?></code></td>
			</tr>
			<tr>
				<td>Dev-Key</td>
				<td><code><?php echo $details['licensekeydev'] ?></code></td>
			</tr>
			</tbody>
		</table>


	<?php endforeach ?>

<?php endif ?>