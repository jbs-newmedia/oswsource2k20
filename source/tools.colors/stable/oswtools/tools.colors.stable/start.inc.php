<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package oswFrame - Tools
 * @link http://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 *
 */

/*
 * TOOL - Start
 */

$data=array('hex'=>'HEX', 'rgb'=>'RGB');
$type=osW_Tool::getInstance()->getInstance()->_catch('type', 'hex', 'pg');
if (!isset($data[$type])) {
	$type='hex';
}

?>

<div class="container">

	<ul class="nav nav-tabs">
	<?php foreach($data as $key => $value):?>
		<li<?php if($type==$key):?> class="active"<?php endif?>><a href="index.php?session=<?php echo osW_Tool_Session::getInstance()->getId()?>&action=<?php echo osW_Tool::getInstance()->getAction()?>&type=<?php echo $key?>"><?php echo $value?></a></li>
	<?php endforeach?>
	</ul>

	<br>

<?php if($type=='hex'):?>
	<table class="table table-bordered">
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
<?php endif?>

<?php if($type=='rgb'):?>
	<table class="table table-bordered">
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
<?php endif?>

</div>