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
 * TOOL - Start
 */

if (isset($settings['ignore'])) {
	$ar_list=osW_Tool_ProjectVerify::getInstance()->getList($settings['ignore']);
} else {
	$ar_list=osW_Tool_ProjectVerify::getInstance()->getList(array());
}

$error=array();
$messages=array();

if (osW_Tool::getInstance()->getDoAction()=='dozip') {
	osW_Tool_ProjectVerify::getInstance()->createUpdatePackageZIP($ar_list);
}

?>

<div class="container">

<?php /*
	<p>Please select your directories/files you want to clear and confirm your input by pressing the button "Clear selected directories/files". You can ignore directories/file by pressing the button "X" on the end of the line.</p>

	<hr/>
*/ ?>

<?php

osW_Tool_Template::getInstance()->outputB3Alerts();


?>

<form id="oswtools_projectverify" method="post" action="index.php?session=<?php echo osW_Tool_Session::getInstance()->getId()?>&action=start">

	<table id="projectverify_main" class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Status</th>
				<th>Directory/File</th>
			</tr>
		</thead>
		<tbody>

<?php if(count($ar_list)>0):?>
<?php $i=0;foreach ($ar_list as $element => $status):$i++;?>
			<tr>
				<td><?php if($status==1):?>changed<?php elseif($status==2):?>new<?php elseif($status==3):?>deleted<?php else:?>undefined<?php endif?></td>
				<td><?php echo $element?>
				<div class="pull-right"><a title="Ignore" href="javascript:engine('<?php echo $element?>', '<?php echo osW_Tool_Session::getInstance()->getId()?>')" class="remove buttonosw"><i class="fa fa-remove fa-fw"></i></a></div>
				</td>
			</tr>
<?php endforeach?>
<?php else:?>
			<tr>
				<td colspan="2">Nothing changed</td>
			</tr>
<?php endif?>
		</tbody>
	</table>


<?php if(count($ar_list)>0):?>

	<hr/>

	<div class="form-group ">
		<button type="button" class="btn btn-primary btn-block" onclick="$('#oswtools_projectverify').submit()" form="oswtools_projectverify">Create and download ZIP-archive</button>
	</div>

	<input type="hidden" name="doaction" value="dozip"/>
<?php endif?>
</form>

</div>
