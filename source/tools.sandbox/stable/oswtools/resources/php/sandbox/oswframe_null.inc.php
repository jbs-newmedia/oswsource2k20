<?php

$sandbox_title='Check isset, if and empty';

$values=array();
$values[]=null;
$values[]=0;
$values[]=false;
$values[]="";
$values[]=true;
$values[]=1;
$values[]=1.3;
$values[]=array();

$cmds=array();
$cmds[]=array('cmd'=>'return isset($v);', 'title'=>'isset($v)');
$cmds[]=array('cmd'=>'if($v){return true;}else{return false;}', 'title'=>'if($v)');
$cmds[]=array('cmd'=>'return empty($v);', 'title'=>'empty($v)');

function sandbox_check($cmd, $v) {
	return eval($cmd);
}

function sandbox_checktyp($v) {
	if (is_null($v)) {
		return '<span title="null" class="null">null</span>';
	}

	if ((is_bool($v))&&($v===true)) {
		return '<span title="boolean" class="true">true</span>';
	}

	if ((is_bool($v))&&($v===false)) {
		return '<span title="boolean" class="false">false</span>';
	}

	if (is_string($v)) {
		return '<span title="string" class="string">"'.$v.'"</span>';
	}

	if (is_int($v)) {
		return '<span title="integer" class="integer">'.$v.'</span>';
	}

	if (is_float($v)) {
		return '<span title="float" class="float">'.$v.'</span>';
	}

	if (is_array($v)) {
		return '<span title="array" class="array">'.serialize($v).'</span>';
	}
}

?>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>$v=</th>
<?php foreach($values as $v):?>
			<th><?php echo sandbox_checktyp($v);?></th>
<?php endforeach?>
		</tr>
	</thead>
	<tbody>
<?php foreach($cmds as $cmd):?>
		<tr>
			<th><?php echo $cmd['title'];?></th>
<?php foreach($values as $v):?>
			<td><?php echo sandbox_checktyp(sandbox_check($cmd['cmd'], $v))?></td>
<?php endforeach?>
		</tr>
<?php endforeach?>
	</tbody>
</table>