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
 * @var \osWFrame\Tools\Tool\LicenseKey $Tool
 *
 */

?>

<?php if (in_array(\osWFrame\Core\Settings::getAction(), ['about'], true)): ?>

	<?php include \osWFrame\Core\Settings::getStringVar('settings_abspath') . 'resources' . \DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR . 'about.tpl.php'; ?>

<?php elseif (in_array(\osWFrame\Core\Settings::getAction(), ['changelog'], true)): ?>

	<?php include \osWFrame\Core\Settings::getStringVar('settings_abspath') . 'resources' . \DIRECTORY_SEPARATOR . 'tpl' . \DIRECTORY_SEPARATOR . 'changelog.tpl.php'; ?>

<?php else: ?>

	<?php foreach ($Tool->getLicenseList() as $key => $details): ?>

		<table class="table table-striped table-bordered">
			<thead>
			<tr>
				<th colspan="2"><?php echo \osWFrame\Core\HTML::outputString($details['server_list']) ?></th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>Frame-Key</td>
				<td><code><?php echo $details['frame_key'] ?></code></td>
			</tr>
			<tr>
				<td>Account-Email</td>
				<td><code><?php echo $details['account_email'] ?></code></td>
			</tr>
			<tr>
				<td>License-Key</td>
				<td><code><?php echo $details['license_key'] ?></code></td>
			</tr>
			</tbody>
		</table>


	<?php endforeach ?>

<?php endif ?>
