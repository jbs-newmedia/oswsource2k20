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

$patcher_file=osW_Tool::getInstance()->_catch('patcher_file', '', 'pg');

$ar_patcher_files=array();
foreach (glob(abs_path.'resources/php/patcher/*.inc.php') as $file) {
	preg_match('/\$patcher_title\=\'(.*)\'\;/Uis', file_get_contents($file), $patcher_files);
	if (isset($patcher_files[1])) {
		$patcher_files=$patcher_files[1];
	} else {
		$patcher_files=basename($file);
	}
	$ar_patcher_files[basename($file)]=$patcher_files;
}

?>

<div class="container">

<?php if($ar_patcher_files!=array()):?>

	<form id="patcher_start" method="post" action="index.php?session=<?php echo osW_Tool_Session::getInstance()->getId()?>&action=start">

	<div class="row">

		<div class="col-xs-8 col-sm-10">
				<select title="select patcher" name="patcher_file" class="form-control selectpicker">
				<?php foreach($ar_patcher_files as $key => $value):?>
					<option value="<?php echo $key?>"<?php if($patcher_file==$key):?> selected="selected"<?php endif?>><?php echo $value?></option>
				<?php endforeach?>
				</select>
		</div>
		<div class="col-xs-4 col-sm-2"><button type="button" class="btn btn-primary btn-block" onclick="$('#patcher_start').submit()" form="chmod_start">Run</button></div>
	</div>

	<input type="hidden" name="doaction" value="doit"/>

	</form>

	<hr/>

	<?php if (($patcher_file!='')&&(isset($ar_patcher_files[$patcher_file]))):?>
	<?php include abs_path.'resources/php/patcher/'.$patcher_file ?>
	<?php endif?>

<?php else:?>

	<div class="row">
		<div class="col-xs-12">
			No patcher-files available
		</div>
	</div>

<?php endif?>
</div>