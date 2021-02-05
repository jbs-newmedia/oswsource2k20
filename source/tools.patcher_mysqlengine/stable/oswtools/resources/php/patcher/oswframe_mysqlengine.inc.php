<?php

$patcher_title='MySQLEngine';

$db=array(
	'type'=>'mysql',
	'database'=>osW_Tool::getInstance()->getFrameConfig('database_db'),
	'server'=>osW_Tool::getInstance()->getFrameConfig('database_server'),
	'username'=>osW_Tool::getInstance()->getFrameConfig('database_username'),
	'password'=>osW_Tool::getInstance()->getFrameConfig('database_password'),
	'pconnect'=>false,
	'prefix'=>osW_Tool::getInstance()->getFrameConfig('database_prefix')
);

$default_engine=osW_Tool::getInstance()->getFrameConfig('database_engine');
$default_charset=osW_Tool::getInstance()->getFrameConfig('database_character');
$default_collation=osW_Tool::getInstance()->getFrameConfig('database_collation');

osW_Tool_Database::addDatabase('default', $db);
$dbstatus=osW_Tool_Database::connect('default', $db);

$mysql=array();

if ($dbstatus->error===false) {
	$mysql['info']['version']=osW_Tool_Database::getInstance()->info();
	$mysql['info']['table_engine']=false;
	$mysql['info']['table_collation']=false;
	$mysql['info']['table_column']=false;

	$Qget=osW_Tool_Database::getInstance()->query('SELECT @@character_set_database, @@collation_database;');
	$Qget->execute();
	if ($Qget->numberOfRows()==1) {
		$Qget->next();
		if ((isset($Qget->result['@@collation_database']))&&($Qget->result['@@collation_database']!=$default_collation)) {
			$mysql['info']['collation']=$Qget->result['@@collation_database'];
			$mysql['info']['collation_patch']=true;
		}
		if ((isset($Qget->result['@@character_set_database']))&&($Qget->result['@@character_set_database']!=$default_charset)) {
			$mysql['info']['character']=$Qget->result['@@character_set_database'];
			$mysql['info']['character_patch']=true;
		}
	}

	$Qget=osW_Tool_Database::getInstance()->query('SHOW FULL TABLES');
	$Qget->execute();
	if ($Qget->numberOfRows()>0) {
		while ($Qget->next()) {
			if ($Qget->result['Table_type']=='BASE TABLE') {
				$QreadData=osW_Tool_Database::getInstance()->query('SHOW TABLE STATUS FROM :database_db: LIKE :table:');
				$QreadData->bindRaw(':database_db:', osW_Tool::getInstance()->getFrameConfig('database_db'));
				$QreadData->bindValue(':table:', $Qget->result['Tables_in_'.osW_Tool::getInstance()->getFrameConfig('database_db')]);
				$QreadData->execute();
				if ($QreadData->numberOfRows()==1) {
					$QreadData->next();

					$unset=true;

					$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]=array();
					$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['engine']=$default_engine;
					$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['engine_patch']=false;
					$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['collation']=$default_collation;
					$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['collation_patch']=false;
					if ((isset($QreadData->result['Engine']))&&($QreadData->result['Engine']!=$default_engine)) {
						$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['engine']=$QreadData->result['Engine'];
						$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['engine_patch']=true;
						$unset=false;
						$mysql['info']['table_engine']=true;
					}
					if ((isset($QreadData->result['Collation']))&&($QreadData->result['Collation']!=$default_collation)) {
						$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['collation']=$QreadData->result['Collation'];
						$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['collation_patch']=true;
						$unset=false;
						$mysql['info']['table_collation']=true;
					}

					$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['columns']=array();

					$QreadDataTbl=osW_Tool_Database::getInstance()->query('SHOW FULL COLUMNS FROM :database_tbl:');
					$QreadDataTbl->bindRaw(':database_tbl:', osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']);
					$QreadDataTbl->execute();
					if ($QreadDataTbl->numberOfRows()>0) {
						while ($QreadDataTbl->next()) {
							if ((isset($QreadDataTbl->result['Collation']))&&($QreadDataTbl->result['Collation']!=$default_collation)&&($QreadDataTbl->result['Collation']!=null)) {
								$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['columns'][$QreadDataTbl->result['Field']]['collation']=$QreadDataTbl->result['Collation'];
								$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['columns'][$QreadDataTbl->result['Field']]['collation_patch']=true;
								$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['columns'][$QreadDataTbl->result['Field']]['Type']=$QreadDataTbl->result['Type'];
								$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['columns'][$QreadDataTbl->result['Field']]['Null']=$QreadDataTbl->result['Null'];
								$unset=false;
								$mysql['info']['table_column']=true;
							}
						}
					}

					if ($unset===true) {
						unset($mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]);
					}
				}
			}
		}
	}
}

if (osW_Tool::getInstance()->_catch('patch_action', '', 'pg')=='dopatch') {

	if ((isset($_POST['database_character']))&&($_POST['database_character']==1)) {
		$QupdateTbl=osW_Tool_Database::getInstance()->query('ALTER DATABASE :database_tbl: CHARACTER SET '.$default_charsetn.';');
		$QupdateTbl->bindRaw(':database_tbl:', osW_Tool::getInstance()->getFrameConfig('database_db'));
		$QupdateTbl->execute();
		$reload=true;
	}

	if ((isset($_POST['database_collation']))&&($_POST['database_collation']==1)) {
		$QupdateTbl=osW_Tool_Database::getInstance()->query('ALTER DATABASE :database_tbl: COLLATE '.$default_collation.';');
		$QupdateTbl->bindRaw(':database_tbl:', osW_Tool::getInstance()->getFrameConfig('database_db'));
		$QupdateTbl->execute();
		$reload=true;
	}

	if(count($mysql['tables'])>0) {
		foreach ($mysql['tables'] as $name => $element) {
			if($element['engine_patch']===true) {
				if ((isset($_POST['element_engine'][$name]))&&($_POST['element_engine'][$name]==1)) {
					$QupdateTbl=osW_Tool_Database::getInstance()->query('ALTER TABLE :database_tbl: ENGINE = '.$default_engine.';');
					$QupdateTbl->bindRaw(':database_tbl:', $name);
					$QupdateTbl->execute();
					$reload=true;
					if ($QupdateTbl->query_handler===false) {
						$mysql['tables'][$name]['engine_query']=$QupdateTbl->query;
						$mysql['tables'][$name]['engine_error']=$QupdateTbl->error;
						print_a($QupdateTbl->error);
					}
				}
			}
			if($element['collation_patch']===true) {
				if ((isset($_POST['element_collation'][$name]))&&($_POST['element_collation'][$name]==1)) {
					$QupdateTbl=osW_Tool_Database::getInstance()->query('ALTER TABLE :database_tbl: COLLATE '.$default_collation.';');
					$QupdateTbl->bindRaw(':database_tbl:', $name);
					$QupdateTbl->execute();
					$reload=true;
					if ($QupdateTbl->query_handler===false) {
						$mysql['tables'][$name]['collation_query']=$QupdateTbl->query;
						$mysql['tables'][$name]['collation_error']=$QupdateTbl->error;
						print_a($QupdateTbl->error);
					}
				}
			}

			if(count($mysql['tables'][$name]['columns'])>0) {
				foreach ($mysql['tables'][$name]['columns'] as $c_name => $column) {
					if ((isset($_POST['element_collation_columns'][$name][$c_name]))&&($_POST['element_collation_columns'][$name][$c_name]==1)) {
						$QupdateTbl=osW_Tool_Database::getInstance()->query('ALTER TABLE :database_tbl: CHANGE :database_column: :database_column: :database_column_type: CHARACTER SET '.$default_charset.' COLLATE '.$default_collation.' :database_column_null:;');
						$QupdateTbl->bindRaw(':database_tbl:', $name);
						$QupdateTbl->bindRaw(':database_column:', $c_name);
						$QupdateTbl->bindRaw(':database_column_type:', $column['Type']);
						if ($column['Null']=='NO') {
							$QupdateTbl->bindRaw(':database_column_null:', 'NOT NULL');
						} else {
							$QupdateTbl->bindRaw(':database_column_null:', '');
						}
						$QupdateTbl->execute();
						if ($QupdateTbl->query_handler===false) {
							$mysql['tables'][$name]['columns']['query']=$QupdateTbl->query;
							$mysql['tables'][$name]['columns']['error']=$QupdateTbl->error;
							print_a($QupdateTbl->error);
						}
					}
				}
			}
		}
	}


	$mysql=array();

	if ($dbstatus->error===false) {
		$mysql['info']['version']=osW_Tool_Database::getInstance()->info();
		$mysql['info']['table_engine']=false;
		$mysql['info']['table_collation']=false;
		$mysql['info']['table_column']=false;

		$Qget=osW_Tool_Database::getInstance()->query('SELECT @@character_set_database, @@collation_database;');
		$Qget->execute();
		if ($Qget->numberOfRows()==1) {
			$Qget->next();
			if ((isset($Qget->result['@@collation_database']))&&($Qget->result['@@collation_database']!=$default_collation)) {
				$mysql['info']['collation']=$Qget->result['@@collation_database'];
				$mysql['info']['collation_patch']=true;
			}
			if ((isset($Qget->result['@@character_set_database']))&&($Qget->result['@@character_set_database']!=$default_charset)) {
				$mysql['info']['character']=$Qget->result['@@character_set_database'];
				$mysql['info']['character_patch']=true;
			}
		}

		$Qget=osW_Tool_Database::getInstance()->query('SHOW FULL TABLES');
		$Qget->execute();
		if ($Qget->numberOfRows()>0) {
			while ($Qget->next()) {
				if ($Qget->result['Table_type']=='BASE TABLE') {
					$QreadData=osW_Tool_Database::getInstance()->query('SHOW TABLE STATUS FROM :database_db: LIKE :table:');
					$QreadData->bindRaw(':database_db:', osW_Tool::getInstance()->getFrameConfig('database_db'));
					$QreadData->bindValue(':table:', $Qget->result['Tables_in_'.osW_Tool::getInstance()->getFrameConfig('database_db')]);
					$QreadData->execute();
					if ($QreadData->numberOfRows()==1) {
						$QreadData->next();

						$unset=true;

						$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]=array();
						$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['engine']=$default_engine;
						$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['engine_patch']=false;
						$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['collation']=$default_collation;
						$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['collation_patch']=false;
						if ((isset($QreadData->result['Engine']))&&($QreadData->result['Engine']!=$default_engine)) {
							$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['engine']=$QreadData->result['Engine'];
							$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['engine_patch']=true;
							$unset=false;
							$mysql['info']['table_engine']=true;
						}
						if ((isset($QreadData->result['Collation']))&&($QreadData->result['Collation']!=$default_collation)) {
							$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['collation']=$QreadData->result['Collation'];
							$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['collation_patch']=true;
							$unset=false;
							$mysql['info']['table_collation']=true;
						}

						$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['columns']=array();

						$QreadDataTbl=osW_Tool_Database::getInstance()->query('SHOW FULL COLUMNS FROM :database_tbl:');
						$QreadDataTbl->bindRaw(':database_tbl:', osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']);
						$QreadDataTbl->execute();
						if ($QreadDataTbl->numberOfRows()>0) {
							while ($QreadDataTbl->next()) {
								if ((isset($QreadDataTbl->result['Collation']))&&($QreadDataTbl->result['Collation']!=$default_collation)&&($QreadDataTbl->result['Collation']!=null)) {
									$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['columns'][$QreadDataTbl->result['Field']]['collation']=$QreadDataTbl->result['Collation'];
									$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['columns'][$QreadDataTbl->result['Field']]['collation_patch']=true;
									$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['columns'][$QreadDataTbl->result['Field']]['Type']=$QreadDataTbl->result['Type'];
									$mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]['columns'][$QreadDataTbl->result['Field']]['Null']=$QreadDataTbl->result['Null'];
									$unset=false;
									$mysql['info']['table_column']=true;
								}
							}
						}

						if ($unset===true) {
							unset($mysql['tables'][osW_Tool::getInstance()->getFrameConfig('database_db').'.'.$QreadData->result['Name']]);
						}
					}
				}
			}
		}
	}
}

?>

<h3>MySQL-Version: <?php echo $mysql['info']['version']?></h3>

<hr/>

<form id="patcher" method="post" action="index.php?session=<?php echo osW_Tool_Session::getInstance()->getId()?>&action=start">

	<?php if((isset($mysql['info']['character_patch']))&&($mysql['info']['character_patch']===true)||(isset($mysql['info']['collation_patch']))&&($mysql['info']['collation_patch']===true)||($mysql['info']['table_engine']===true)||($mysql['info']['table_collation']===true)||($mysql['info']['table_column']===true)):?>

		<div class="btn-group" role="group" aria-label="...">
			<button id="select_all" type="button" class="btn btn-default">Select all</button>
			<button id="select_none" type="button" class="btn btn-default">Select none</button>
			<button id="select_invert" type="button" class="btn btn-default">Invert selection</button>
		</div>

		<hr/>

		<?php if((isset($mysql['info']['character_patch']))&&($mysql['info']['character_patch']===true)):?>
			<table class="table table-striped table-bordered">
				<thead>
				<tr>
					<th style="width:60px; text-align:center;">Patch</th>
					<th>Database (Character=&gt;<?php echo $default_collation?>)</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td style="width:60px; text-align:center;"><input type="checkbox" name="database_character" value="1"/></td>
					<td>Database (current: <?php echo $mysql['info']['character']?>)</td>
				</tr>
				</tbody>
			</table>
		<?php endif?>

		<?php if((isset($mysql['info']['collation_patch']))&&($mysql['info']['collation_patch']===true)):?>
			<table class="table table-striped table-bordered">
				<thead>
				<tr>
					<th style="width:60px; text-align:center;">Patch</th>
					<th>Database (Collation=&gt;<?php echo $default_collation?>)</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td style="width:60px; text-align:center;"><input type="checkbox" name="database_collation" value="1"/></td>
					<td>Database (current: <?php echo $mysql['info']['collation']?>)</td>
				</tr>
				</tbody>
			</table>
		<?php endif?>

		<?php if($mysql['info']['table_engine']===true):?>
			<table class="table table-striped table-bordered">
				<thead>
				<tr>
					<th style="width:60px; text-align:center;">Patch</th>
					<th>Table (Engine=&gt;<?php echo $default_engine ?>)</th>
				</tr>
				</thead>
				<tbody>
				<?php $i=0;foreach ($mysql['tables'] as $name => $element):$i++;?>
					<?php if($element['engine_patch']===true):?>
						<tr>
							<td style="width:60px; text-align:center;"><input type="checkbox" name="element_engine[<?php echo $name?>]" value="1"/></td>
							<td><?php echo $name?> (current: <?php echo $element['engine']?>)<?php if(isset($element['engine_error'])):?><br/><span style="color:blue;"><?php echo $element['engine_query']?></span><br/><span style="color:red;"><?php echo $element['engine_error']?></span><?php endif?></td>
						</tr>
					<?php endif?>
				<?php endforeach?>
				</tbody>
			</table>
		<?php endif?>


		<?php if(($mysql['info']['table_collation']===true)||($mysql['info']['table_column']===true)):?>
			<table class="table table-striped table-bordered">
				<thead>
				<tr>
					<th style="width:60px; text-align:center;">Patch</th>
					<th>Table (Collation=&gt;<?php echo $default_collation?>)</th>
				</tr>
				</thead>
				<tbody>
				<?php $i=0;foreach ($mysql['tables'] as $name => $element):$i++;?>
					<?php if($element['collation_patch']===true):?>
						<tr class="core_data core_data_<?php echo bcmod($i, 2);?>">
							<td style="width:60px; text-align:center;"><input type="checkbox" name="element_collation[<?php echo $name?>]" value="1"/></td>
							<td><?php echo $name?> (current: <?php echo $element['collation']?>)<?php if(isset($element['collation_error'])):?><br/><span style="color:blue;"><?php echo $element['collation_query']?></span><br/><span style="color:red;"><?php echo $element['collation_error']?></span><?php endif?></td>
						</tr>
					<?php endif?>
					<?php if(count($mysql['tables'][$name]['columns'])>0):?>
						<?php foreach ($mysql['tables'][$name]['columns'] as $c_name => $column):$i++;?>
							<tr>
								<td style="width:60px; text-align:center;"><input type="checkbox" name="element_collation_columns[<?php echo $name?>][<?php echo $c_name?>]" value="1"/></td>
								<td><?php echo $name?>.<?php echo $c_name?> (current: <?php echo $column['collation']?>)</td>
							</tr>
						<?php endforeach?>
					<?php endif?>
				<?php endforeach?>
				</tbody>
			</table>
		<?php endif?>

		<hr/>

		<input class="btn btn-primary btn-block" type="submit" name="patch" value="Patch MySQLEngine"/>

	<?php else:?>

		<table class="table table-striped table-bordered">
			<tbody>
			<tr>
				<td colspan="2">Nothing to patch</td>
			</tr>

			</tbody>
		</table>

	<?php endif?>




	<input type="hidden" name="patcher_file" value="<?php echo $patcher_file?>"/>
	<input type="hidden" name="patch_action" value="dopatch"/>

</form>

