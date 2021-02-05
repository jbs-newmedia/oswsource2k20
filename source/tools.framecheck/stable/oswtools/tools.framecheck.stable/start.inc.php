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

foreach (glob(abs_path.'resources/php/framecheck/preload/*.inc.php') as $file) {
	include $file;
}

$ar_framecheck_files=array();
foreach (glob(abs_path.'resources/php/framecheck/*.inc.php') as $file) {
	$ar_framecheck_files[]=$file;
}

$configs=array();
foreach ($ar_framecheck_files as $file) {
	include $file;
	$configs[]=$config;
}

foreach ($configs as $_id => $config) {
	foreach ($config['elements'] as $id => $data) {
		$file=abs_path.'resources/php/framecheck/validator/'.$data['check'].'.inc.php';
		if (file_exists($file)) {
			include $file;
		}
		$configs[$_id]['elements'][$id]=$data;
	}
}

?>

<div class="container">

	<?php foreach ($configs as $_id => $config):?>
	<table class="table table-striped table-bordered">
	<tr>
		<th colspan="4"><?php echo $config['title']?></th>
	</tr>
	<tr>
		<td><strong><i>Function</i></strong></td>
		<td><strong><i>Required</i></strong></td>
		<td><strong><i>Available</i></strong></td>
		<td><strong><i>Result</i></strong></td>
	</tr>
	<?php foreach ($config['elements'] as $id => $data):?>
	<tr>
		<td><?php echo $data['title']?></td>
		<td><?php echo $data['value']?></td>
		<td><?php echo $data['getvalue']?></td>
		<?php if(isset($data['score'])):?>
		<?php
		switch ($data['score']) {
			case 0:
				echo '<td><span style="color:#3c763d"><strong>ok</strong></span></td>';
				break;
			case 5:
				echo '<td><span style="color:#3170a1"><strong>notice</strong></span></td>';
				break;
			case 10:
				echo '<td><span style="color:#a94442"><strong>error</strong></span></td>';
				break;
			default:
				echo '<td>---</td>';
				break;
		}
		?>
		<?php else:?>
		<?php echo '<td>---</td>';?>
		<?php endif?>
	</tr>
	<?php endforeach?>
	</table>
	<?php endforeach?>


</div>