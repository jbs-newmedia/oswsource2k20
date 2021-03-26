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

<h4 class="mb-3">Changelog</h4>

<?php if ($changelog!=[]): ?>
	<div id="accordion">
		<?php foreach ($changelog as $version=>$changes): ?>
			<div class="card mb-2">
				<div class="card-header" id="heading<?php echo md5($version) ?>">
					<h5 class="mb-0 d-block">
						<a class="btn btn-link d-block text-left<?php if ($version!=array_key_first($changelog)): ?> collapsed<?php endif ?>" data-toggle="collapse" data-target="#collapse<?php echo md5($version) ?>" aria-expanded="true" aria-controls="collapse<?php echo md5($version) ?>">
							<?php echo $version ?>
						</a>
					</h5>
				</div>

				<div id="collapse<?php echo md5($version) ?>" class="collapse<?php if ($version==array_key_first($changelog)): ?> show<?php endif?>" aria-labelledby="heading<?php echo md5($version) ?>" data-parent="#accordion">
					<div class="card-body">
						<?php $changes=explode("\n", $changes); ?>
						<ul>
							<?php foreach ($changes as $change): ?>
								<li><?php echo trim(substr($change, 2)) ?></li>
							<?php endforeach ?>
						</ul>
					</div>
				</div>
			</div>
		<?php endforeach ?>
	</div>
<?php else: ?>
	<div class="card mb-2">
		<div class="card-body">No changelog available.</div>
	</div>
<?php endif ?>
