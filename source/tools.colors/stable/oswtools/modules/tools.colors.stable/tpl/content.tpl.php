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

	<ul class="nav nav-tabs">
		<?php foreach ($Tool->getColors() as $key=>$value): ?>
			<li class="nav-item">
				<a class="nav-link<?php if ($Tool->getColor()==$key): ?> active"<?php endif ?>" href="<?php echo $this->buildhrefLink('current', 'type='.$key) ?>"><?php echo $value ?></a>
			</li>
		<?php endforeach ?>
	</ul>

	<?php if ($Tool->getColor()=='hex'): ?>
		<table class="table table-bordered mt-3">
			<tbody>
			<?php
			for ($r=0; $r<16; $r++) {
				for ($g=0; $g<16; $g++) {
					echo '<tr>';
					for ($b=0; $b<16; $b++) {
						if (($r<10)&&($g<8)&&($b<16)) {
							$color='#fff';
						} else {
							$color='#000';
						}
						echo '<td style="color:'.$color.'; text-align:center; background-color:#'.strtoupper(dechex($r).dechex($g).dechex($b).'">#'.dechex($r).dechex($g).dechex($b)).'</td>';
					}
					echo '</tr>';
				}
			}
			?>
			</tbody>
		</table>
	<?php endif ?>

	<?php if ($Tool->getColor()=='rgb'): ?>
		<table class="table table-bordered mt-3">
			<tbody>
			<?php
			for ($r=0; $r<16; $r++) {
				for ($g=0; $g<16; $g++) {
					echo '<tr>';
					for ($b=0; $b<16; $b++) {
						if (($r<10)&&($g<8)&&($b<16)) {
							$color='#fff';
						} else {
							$color='#000';
						}
						echo '<td style="color:'.$color.'; text-align:center; background-color:#'.strtoupper(dechex($r).dechex($g).dechex($b).'">'.sprintf('%03d', hexdec(dechex($r).dechex($r))).' '.sprintf('%03d', hexdec(dechex($g).dechex($g))).' '.sprintf('%03d', hexdec(dechex($b).dechex($b)))).'</td>';
						if ($b==7) {
							echo '</tr>';
							echo '<tr>';
						}
					}
					echo '</tr>';
				}
			}
			?>
			</tbody>
		</table>
	<?php endif ?>

<?php endif ?>