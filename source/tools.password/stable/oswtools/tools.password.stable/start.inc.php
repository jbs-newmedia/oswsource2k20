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

$lengths=array();
$lengths[4]='4 Chars';
$lengths[5]='5 Chars';
$lengths[6]='6 Chars';
$lengths[7]='7 Chars';
$lengths[8]='8 Chars';
$lengths[9]='9 Chars';
$lengths[10]='10 Chars';
$lengths[12]='12 Chars';
$lengths[16]='16 Chars';
$lengths[24]='24 Chars';
$lengths[32]='32 Chars';
$lengths[64]='64 Chars';

if (!isset($_POST['length'])) {
	$_POST['length']=12;
}

if (!isset($_POST['symbol'])) {
	$_POST['symbol']=8;
}

?>

<div class="container">

<select title="length" name="length" class="form-control selectpicker">
<?php foreach($lengths as $length => $title):?>
	<option value="<?php echo $length?>"<?php if($_POST['length']==$length):?> selected="selected"<?php endif?>><?php echo $title?></option>
<?php endforeach?>
</select>

	<hr/>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th>Set</th>
			<th>Numbers</th>
			<th>Symbols</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td style="text-align:center;"><input type="radio" name="symbol" value="1"<?php if((isset($_POST['symbol']))&&($_POST['symbol']==1)):?> checked="checked"<?php endif?>/></td>
			<td>Arabic numerals</td>
			<td>10</td>
			<td>0-9</td>
		</tr>
		<tr>
			<td style="text-align:center;"><input type="radio" name="symbol" value="2"<?php if((isset($_POST['symbol']))&&($_POST['symbol']==2)):?> checked="checked"<?php endif?>/></td>
			<td>hexadecimal numerals</td>
			<td>16</td>
			<td>0-9, A-F</td>
		</tr>
		<tr>
			<td style="text-align:center;"><input type="radio" name="symbol" value="3"<?php if((isset($_POST['symbol']))&&($_POST['symbol']==3)):?> checked="checked"<?php endif?>/></td>
			<td>Case insensitive Latin alphabet</td>
			<td>26</td>
			<td>a-z</td>
		</tr>
		<tr>
			<td style="text-align:center;"><input type="radio" name="symbol" value="4"<?php if((isset($_POST['symbol']))&&($_POST['symbol']==4)):?> checked="checked"<?php endif?>/></td>
			<td>Case insensitive Latin alphabet</td>
			<td>26</td>
			<td>A-Z</td>
		</tr>
		<tr>
			<td style="text-align:center;"><input type="radio" name="symbol" value="5"<?php if((isset($_POST['symbol']))&&($_POST['symbol']==5)):?> checked="checked"<?php endif?>/></td>
			<td>Case insensitive alphanumeric</td>
			<td>36</td>
			<td>a-z, 0-9</td>
		</tr>
		<tr>
			<td style="text-align:center;"><input type="radio" name="symbol" value="6"<?php if((isset($_POST['symbol']))&&($_POST['symbol']==6)):?> checked="checked"<?php endif?>/></td>
			<td>Case insensitive alphanumeric</td>
			<td>36</td>
			<td>A-Z, 0-9</td>
		</tr>
		<tr>
			<td style="text-align:center;"><input type="radio" name="symbol" value="7"<?php if((isset($_POST['symbol']))&&($_POST['symbol']==7)):?> checked="checked"<?php endif?>/></td>
			<td>Case sensitive Latin alphabet</td>
			<td>52</td>
			<td>a-z, A-Z</td>
		</tr>
		<tr>
			<td style="text-align:center;"><input type="radio" name="symbol" value="8"<?php if((isset($_POST['symbol']))&&($_POST['symbol']==8)):?> checked="checked"<?php endif?>/></td>
			<td>Case sensitive alphanumeric</td>
			<td>62</td>
			<td>a-z, A-Z, 0-9</td>
		</tr>
		<tr>
			<td style="text-align:center;"><input type="radio" name="symbol" value="9"<?php if((isset($_POST['symbol']))&&($_POST['symbol']==9)):?> checked="checked"<?php endif?>/></td>
			<td>All ASCII printable characters</td>
			<td>93</td>
			<td>a-z, A-Z, 0-9, !-~</td>
		</tr>
	</tbody>
</table>

	<hr/>

<div class="panel panel-default">
  <div class="panel-body" style="text-align:center; font-size:20px; font-weight:bold;" id="generatedpassword">-</div>
</div>

	<hr/>

	<div class="form-group ">
		<button type="button" class="btn btn-primary btn-block" onclick="generatepassword()">Generate password</button>
	</div>



</div>
