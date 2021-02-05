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

$chars_lower=array();
$chars_lower[0]='none';
$chars_lower[1]='[a-z]';
$chars_lower[2]='[a-z] -l';
$chars_lower_select=1;

$chars_upper=array();
$chars_upper[0]='none';
$chars_upper[1]='[A-Z]';
$chars_upper[2]='[A-Z] -OIJ';
$chars_upper_select=1;

$numbers=array();
$numbers[0]='none';
$numbers[1]='[0-9]';
$numbers[2]='[0-9] -O1';
$numbers_select=1;

if (osW_Tool::getInstance()->getDoAction()=='doit') {

	print_a($_POST);;

	$chars=array();
	if ((isset($_POST['codes_chars_lower']))&&($_POST['codes_chars_lower']=='1')) {
		$chars=array_merge($chars,array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'));
	}

	if ((isset($_POST['codes_chars_lower']))&&($_POST['codes_chars_lower']=='2')) {
		$chars=array_merge($chars,array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'));
	}

	if ((isset($_POST['codes_chars_upper']))&&($_POST['codes_chars_upper']=='1')) {
		$chars=array_merge($chars,array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'));
	}

	if ((isset($_POST['codes_chars_upper']))&&($_POST['codes_chars_upper']=='2')) {
		$chars=array_merge($chars,array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'));
	}

	if ((isset($_POST['codes_numbers']))&&($_POST['codes_numbers']=='1')) {
		$chars=array_merge($chars,array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9'));
	}

	if ((isset($_POST['codes_numbers']))&&($_POST['codes_numbers']=='2')) {
		$chars=array_merge($chars,array('2', '3', '4', '5', '6', '7', '8', '9'));
	}

	if ($chars==array()) {
		$chars=array_merge($chars,array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9'));
	}

	if (isset($_POST['prefix'])) {
		$prefix=$_POST['prefix'];
	} else {
		$prefix='OSW';
	}

	if (isset($_POST['suffix'])) {
		$suffix=$_POST['suffix'];
	} else {
		$suffix='';
	}

	if (isset($_POST['length'])) {
		$length=intval($_POST['length']);
	} else {
		$length=10;
	}

	if (isset($_POST['codes_count'])) {
		$codes_count=intval($_POST['codes_count']);
	} else {
		$codes_count=500;
	}

	if ($codes_count<1) {
		$codes_count=1;
	}

	if ($length<1) {
		$length=1;
	}

	$chars_count=count($chars);
	$codes=array();

	// erstellt einen Code
	function make_code() {
		global $chars, $chars_count, $prefix, $suffix, $length;
		$code=$prefix.'';
		for ($i=1; $i<=$length; $i++) {
			$code.=$chars[mt_rand(0, $chars_count-1)];
		}
		$code.=$suffix;
		return $code;
	}

	list($usec, $sec)=explode(' ', microtime());
	$seed=(float)$sec+((float)$usec*100000);

	mt_srand($seed);

	$time_start = microtime(true);

	$i=0;
	while($i<$codes_count) {
		$codes[make_code()]='';
		$i=count($codes);
	}

	$time_end = microtime(true);
	$time = $time_end - $time_start;

	$filename=array();
	$filename[]='Codes';
	if ($codes_count!='') {
		$filename[]=$codes_count;
	}
	if ($prefix!='') {
		$filename[]=$prefix;
	}
	if ($suffix!='') {
		$filename[]=$suffix;
	}

	ob_clean();
	header('Content-Disposition: attachment; filename="'.implode('_', $filename).'.csv"');
	header("Content-Type: text/plain");

	foreach ($codes as $code => $blank) {
		echo $code."\r\n";
	}

	die();
}

?>

<div class="container">

	<p>Please configure your personal codes.</p>

	<hr/>

	<?php osW_Tool_Template::getInstance()->outputB3Alerts()?>

	<form id="codes_start" method="post" action="index.php?session=<?php echo osW_Tool_Session::getInstance()->getId()?>&action=start">

	<div class="form-group<?php if(isset($error['codes_prefix'])):?> has-error<?php endif?>">
		<label for="codes_prefix" class="control-label">Prefix:</label>
		<div>
			<input class="form-control" name="prefix" type="text" value="OSW" />
			<?php if(isset($error['codes_prefix'])):?><span class="help-block"><?php echo $error['codes_prefix']?></span><?php endif?>
		</div>
	</div>

	<div class="form-group<?php if(isset($error['codes_suffix'])):?> has-error<?php endif?>">
		<label for="codes_suffix" class="control-label">Suffix:</label>
		<div>
			<input class="form-control" name="suffix" type="text" value="" />
			<?php if(isset($error['codes_suffix'])):?><span class="help-block"><?php echo $error['codes_suffix']?></span><?php endif?>
		</div>
	</div>

	<div class="form-group<?php if(isset($error['codes_length'])):?> has-error<?php endif?>">
		<label for="codes_length" class="control-label">Length:</label>
		<div>
			<input class="form-control" name="length" type="text" value="10" />
			<?php if(isset($error['codes_length'])):?><span class="help-block"><?php echo $error['codes_length']?></span><?php endif?>
		</div>
	</div>

	<div class="form-group<?php if(isset($error['codes_codes_count'])):?> has-error<?php endif?>">
		<label for="codes_codes_count" class="control-label">Number of codes:</label>
		<div>
			<input class="form-control" name="codes_count" type="text" value="1" />
			<?php if(isset($error['codes_codes_count'])):?><span class="help-block"><?php echo $error['codes_codes_count']?></span><?php endif?>
		</div>
	</div>

	<div class="form-group<?php if(isset($error['codes_chars_lower'])):?> has-error<?php endif?>">
		<label for="codes_chars_lower" class="control-label">Chars (lower):</label>
		<div>
			<select title="select chars" name="codes_chars_lower" class="form-control selectpicker">
			<?php foreach($chars_lower as $key => $value):?>
				<option value="<?php echo $key?>"<?php if($chars_lower_select==$key):?> selected="selected"<?php endif?>><?php echo $value?></option>
			<?php endforeach?>
			</select>
			<?php if(isset($error['codes_chars_lower'])):?><span class="help-block"><?php echo $error['codes_chars_lower']?></span><?php endif?>
		</div>
	</div>

	<div class="form-group<?php if(isset($error['codes_chars_upper'])):?> has-error<?php endif?>">
		<label for="codes_chars_upper" class="control-label">Chars (upper):</label>
		<div>
			<select title="select chars" name="codes_chars_upper" class="form-control selectpicker">
			<?php foreach($chars_upper as $key => $value):?>
				<option value="<?php echo $key?>"<?php if($chars_upper_select==$key):?> selected="selected"<?php endif?>><?php echo $value?></option>
			<?php endforeach?>
			</select>
			<?php if(isset($error['codes_chars_upper'])):?><span class="help-block"><?php echo $error['codes_chars_upper']?></span><?php endif?>
		</div>
	</div>

	<div class="form-group<?php if(isset($error['codes_numbers'])):?> has-error<?php endif?>">
		<label for="codes_numbers" class="control-label">Numbers:</label>
		<div>
			<select title="select chars" name="codes_numbers" class="form-control selectpicker">
			<?php foreach($numbers as $key => $value):?>
				<option value="<?php echo $key?>"<?php if($numbers_select==$key):?> selected="selected"<?php endif?>><?php echo $value?></option>
			<?php endforeach?>
			</select>
			<?php if(isset($error['codes_numbers'])):?><span class="help-block"><?php echo $error['codes_numbers']?></span><?php endif?>
		</div>
	</div>

	<hr/>

	<div class="form-group ">
		<button type="button" class="btn btn-primary btn-block" onclick="$('#codes_start').submit()" form="codes_start">Create codes</button>
	</div>

	<input type="hidden" name="doaction" value="doit"/>
	</form>

</div>
