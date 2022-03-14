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

<h4 class="mb-3">Changelog</h4>

<?php if ($changelog!=[]): ?>
	<div class="accordion" id="accordionChangelog">
<?php foreach ($changelog as $version=>$changes): ?>
		<div class="accordion-item">
			<h2 class="accordion-header" id="heading<?php echo md5($version) ?>">
				<button class="accordion-button<?php if ($version!=array_key_first($changelog)): ?> collapsed<?php endif ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo md5($version) ?>" aria-expanded="true" aria-controls="collapse<?php echo md5($version) ?>">
					<?php echo $version ?>
				</button>
			</h2>

			<div id="collapse<?php echo md5($version) ?>" class="accordion-collapse collapse<?php if ($version==array_key_first($changelog)): ?> show<?php endif ?>" aria-labelledby="heading<?php echo md5($version) ?>" data-bs-parent="#accordionChangelog">
				<div class="accordion-body">
					<?php $changes=explode("\n", $changes); ?>
					<ul>
						<?php foreach ($changes as $change): ?>
							<li><?php echo trim(substr($change, 2)) ?></li>
						<?php endforeach ?>
					</ul>
				</div>
			</div>
		</div>
<?php endforeach;?>
	</div>
<?php else: ?>
	<div class="card mb-2">
		<div class="card-body">No changelog available.</div>
	</div>
<?php endif ?>
