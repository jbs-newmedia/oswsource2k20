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

$ar_list=array();

$ar_list=osW_Tool_FrameVerify::getInstance()->getList(root_path.'frame');

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

	<table id="frameverify_main" class="table table-striped table-bordered">
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
				<td><?php echo $element?></td>
			</tr>
<?php endforeach?>
<?php endif?>
		</tbody>
	</table>

</div>
