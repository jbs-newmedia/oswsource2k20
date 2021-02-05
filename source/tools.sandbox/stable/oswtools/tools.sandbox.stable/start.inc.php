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

$sandbox_file=osW_Tool::getInstance()->_catch('sandbox_file', '', 'pg');

$ar_sandbox_files=array();
foreach (glob(abs_path.'resources/php/sandbox/*.inc.php') as $file) {
	preg_match('/\$sandbox_title\=\'(.*)\'\;/Uis', file_get_contents($file), $sandbox_files);
	if (isset($sandbox_files[1])) {
		$sandbox_files=$sandbox_files[1];
	} else {
		$sandbox_files=basename($file);
	}
	$ar_sandbox_files[basename($file)]=$sandbox_files;
}

?>

<div class="container">

	<form id="sandbox_start" method="post" action="index.php?session=<?php echo osW_Tool_Session::getInstance()->getId()?>&action=start">

	<div class="row">
		<div class="col-xs-8 col-sm-10">
				<select title="select sandbox" name="sandbox_file" class="form-control selectpicker">
				<?php foreach($ar_sandbox_files as $key => $value):?>
					<option value="<?php echo $key?>"<?php if($sandbox_file==$key):?> selected="selected"<?php endif?>><?php echo $value?></option>
				<?php endforeach?>
				</select>

		</div>
		<div class="col-xs-4 col-sm-2"><button type="button" class="btn btn-primary btn-block" onclick="$('#sandbox_start').submit()" form="chmod_start">Run</button></div>
	</div>

	<input type="hidden" name="doaction" value="doit"/>

	</form>

	<hr/>

	<?php if (($sandbox_file!='')&&(isset($ar_sandbox_files[$sandbox_file]))):?>
	<?php include abs_path.'resources/php/sandbox/'.$sandbox_file ?>
	<?php endif?>

</div>