<?php

$patcher_title='PHPShortTag';

$patch_list=array();

$patch_list=osW_Tool_Patcher_PHPShortTag::getInstance()->getList(root_path.'frame');

if (osW_Tool::getInstance()->_catch('patch_action', '', 'pg')=='dopatch') {
	if ((isset($_POST['element']))||(count($patch_list)>0)) {
		foreach ($patch_list as $element) {
			if ((isset($_POST['element'][$element]))&&($_POST['element'][$element]==1)) {
				if (file_exists(root_path.$element)) {
					osW_Tool_Patcher_PHPShortTag::getInstance()->patchFile(root_path.$element);
				}
			}
		}
		$patch_list=osW_Tool_Patcher_PHPShortTag::getInstance()->getList(root_path.'frame');
	}
}

?>

<form id="patcher" method="post" action="index.php?session=<?php echo osW_Tool_Session::getInstance()->getId()?>&action=start">

<?php if(count($patch_list)>0):?>

	<div class="btn-group" role="group" aria-label="...">
		<button id="select_all" type="button" class="btn btn-default">Select all</button>
		<button id="select_none" type="button" class="btn btn-default">Select none</button>
		<button id="select_invert" type="button" class="btn btn-default">Invert selection</button>
	</div>

	<hr/>

<?php endif?>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th style="width:60px; text-align:center;">Patch</th>
			<th>File</th>
		</tr>
	</thead>
	<tbody>
<?php if(count($patch_list)>0):?>
<?php $i=0;foreach ($patch_list as $element):$i++;?>
		<tr>
			<td style="width:60px; text-align:center;"><input type="checkbox" name="element[<?php echo $element?>]" value="1" checked="checked"/></td>
			<td><?php echo $element?></td>
		</tr>
<?php endforeach?>
<?php else:?>
		<tr>
			<td colspan="2">Nothing to patch</td>
		</tr>
<?php endif?>
	</tbody>
</table>

<?php if(count($patch_list)>0):?>
<hr/>

<input class="btn btn-primary btn-block" type="submit" name="patch" value="Patch PHPShortTags"/>
<?php endif?>

<input type="hidden" name="patcher_file" value="<?php echo $patcher_file?>"/>
<input type="hidden" name="patch_action" value="dopatch"/>

</form>