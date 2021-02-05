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

$clear_list=osW_Tool_FrameClear::getInstance()->getList(root_path.'frame');

$error=array();
$messages=array();

if (osW_Tool::getInstance()->getDoAction()=='doclear') {
	$i=0;

	if ((isset($_POST['element']))||(count($clear_list)>0)) {
		foreach ($clear_list as $element) {
			if ((isset($_POST['element'][$element]))&&($_POST['element'][$element]==1)) {
				if (is_dir(root_path.$element)) {
					$i++;
					osW_Tool::getInstance()->delTree(root_path.$element);
				} elseif (is_file(root_path.$element)) {
					$i++;
					osW_Tool::getInstance()->delFile(root_path.$element);
				}
			}
		}
	}

	if(count($error)==0) {
		if ($i==0) {
			$messages['info'][]='No directories/file were cleared.';
		} elseif ($i==1) {
			$messages['success'][]=$i.' directory/file was cleared.';
		} else {
			$messages['success'][]=$i.' directories/files were cleared.';
		}
		osW_Tool_Session::getInstance()->set('messages', $messages);
		osW_Tool::getInstance()->_direct('index.php?session='.osW_Tool_Session::getInstance()->getId().'&action=start');
	}
}

?>

<div class="container">

	<div class="btn-group" role="group" aria-label="...">
		<button id="select_all" type="button" class="btn btn-default">Select all</button>
		<button id="select_none" type="button" class="btn btn-default">Select none</button>
		<button id="select_invert" type="button" class="btn btn-default">Invert selection</button>
	</div>

	<br/><br/>

	<p>Please select your directories/files you want to clear and confirm your input by pressing the button "Clear selected directories/files".</p>

	<hr/>

<?php

osW_Tool_Template::getInstance()->outputB3Alerts();


?>

<form id="oswtools_frameclear" method="post" action="index.php?session=<?php echo osW_Tool_Session::getInstance()->getId()?>&action=start">

	<table id="frameclear_main" class="table table-striped table-bordered">
		<thead>
			<tr>
				<th style="width:50px;" class="text-center">Clear</th>
				<th>Directory/File</th>
			</tr>
		</thead>
		<tbody>
<?php if(count($clear_list)>0):?>
<?php $i=0;foreach ($clear_list as $dir):$i++;?>
			<tr>
				<td class="text-center"><input type="checkbox" name="element[<?php echo $dir?>]" value="1"/></td>
				<td><?php echo $dir?></td>
			</tr>
<?php endforeach?>
<?php endif?>
		</tbody>
	</table>


<?php if(count($clear_list)>0):?>

	<hr/>

	<div class="form-group ">
		<button type="button" class="btn btn-primary btn-block" onclick="$('#oswtools_frameclear').submit()" form="oswtools_frameclear">Clear selected directories/files</button>
	</div>

	<input type="hidden" name="doaction" value="doclear"/>
<?php endif?>
</form>

</div>
