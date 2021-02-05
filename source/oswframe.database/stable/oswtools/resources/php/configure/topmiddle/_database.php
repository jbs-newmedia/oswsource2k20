<?php

$this->data['settings']=[];

$this->data['settings']['data']=['page_title'=>'Create/Update Database-Tables',];

if (($position=='run')&&(isset($_POST['next']))&&($_POST['next']=='next')) {
	if ((isset($this->data['values_json']['database_db']))&&(isset($this->data['values_json']['database_db']))&&(isset($this->data['values_json']['database_db']))&&(isset($this->data['values_json']['database_db']))) {
		osW_Tool_Database::addDatabase('default', ['type'=>'mysql', 'database'=>$this->data['values_json']['database_db'], 'server'=>$this->data['values_json']['database_server'], 'username'=>$this->data['values_json']['database_username'], 'password'=>$this->data['values_json']['database_password'], 'pconnect'=>false, 'prefix'=>$this->data['values_json']['database_prefix']]);

		$files=glob(abs_path.'resources/php/configure/database/*.php');

		$tables_create=[];
		$tables_do=[];
		$tables_error=[];
		$db_error=[];
		foreach ($files as $file) {
			include $file;
			if ($__datatable_create===true) {
				$tables_create[]=$__datatable_table;
			}
			if ($__datatable_do===true) {
				$tables_do[]=$__datatable_table;
			}
		}

		if (($tables_create!==[])||($tables_do!==[])) {
			$this->data['messages'][]='Database-Tables were created/updated successfully';
			if ($tables_create!==[]) {
				$this->data['messages'][]='Tables created: '.implode(', ', $tables_create);
			}
			if ($tables_do!==[]) {
				$this->data['messages'][]='Tables updated: '.implode(', ', $tables_do);
			}
			if ($tables_error!==[]) {
				foreach ($tables_error as $key=>$error) {
					$this->data['error'][]='Tables with error: '.$error;
					$this->data['error'][]='Error: '.$db_error[$key];
					$this->data['error'][]='---';
				}
			}
		} else {
			$this->data['messages'][]='Database-Tables nothing to do';
		}
	} else {
		$this->data['messages'][]='Database-Tables creation/update was skipped (there is no database configured)';
	}
}

if (($position=='run')&&(isset($_POST['prev']))&&($_POST['prev']=='prev')) {
	$this->data['messages'][]='Database-Tables creation/update was skipped (go to previous page)';
}

?>