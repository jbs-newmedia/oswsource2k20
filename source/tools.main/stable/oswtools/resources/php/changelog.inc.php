<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package oswFrame - Tools
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 *
 */

/*
 * TOOL - Changelog
 */

$filename=abs_path.'resources/json/changelog/'.package.'-'.release.'.json';

$changelog=array();
if (file_exists($filename)) {
	$changelog=json_decode(file_get_contents($filename), true);
}

?>

<div class="container">
<?php if($changelog!=array()):?>
	<div class="panel-group" id="changelog">
	    <?php $i=0;foreach($changelog as $version=>$changes):$i++?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle<?php if($i>1):?> collapsed<?php endif?> d-block" data-toggle="collapse" data-parent="#changelog" href="#changelog_<?php echo str_replace('.', '_', $version)?>"><?php echo $version?></a>
					</h4>
				</div>
				<div id="changelog_<?php echo str_replace('.', '_', $version)?>" class="panel-collapse collapse<?php if($i==1):?> in<?php endif?>">
					<div class="panel-body">
						<?php $changes=explode("\n", $changes);?>
						<ul>
						<?php foreach($changes as $change):?>
							<li><?php echo trim(substr($change, 2))?></li>
						<?php endforeach?>
						</ul>
					</div>
				</div>
			</div>
		<?php endforeach?>
    </div>
<?php else:?>
<div class="panel-group" id="changelog">
	<div class="panel panel-default">
		<div class="panel-collapse">
			<div class="panel-body">
				No changelog available
			</div>
		</div>
	</div>
<?php endif?>
</div>