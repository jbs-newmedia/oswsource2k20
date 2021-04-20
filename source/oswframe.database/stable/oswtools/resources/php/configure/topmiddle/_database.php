<?php

$this->settings=['page_title'=>'Create/Update Database-Tables'];

if (($position=='run')&&(isset($_POST['next']))&&($_POST['next']=='next')) {
	if (($this->getJSONStringValue('database_server')!=='')&&($this->getJSONStringValue('database_username')!=='')&&($this->getJSONStringValue('database_db')!=='')) {
		\osWFrame\Core\DB::addConnectionMYSQL($this->getJSONStringValue('database_server'), $this->getJSONStringValue('database_username'), $this->getJSONStringValue('database_password'), $this->getJSONStringValue('database_db'));
		if (\osWFrame\Core\DB::connect()===true) {
			$tables_create=[];
			$tables_do=[];
			$tables_error=[];
			$db_error=[];

			$files=glob(\osWFrame\Core\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'configure'.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'*.php');
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
				\osWFrame\Core\MessageStack::addMessage('configure', 'success', ['msg'=>'Database-tables: created/updated successfully.']);
				if ($tables_create!==[]) {
					\osWFrame\Core\MessageStack::addMessage('configure', 'success', ['msg'=>'Tables created: '.implode(', ', $tables_create)]);
				}
				if ($tables_do!==[]) {
					\osWFrame\Core\MessageStack::addMessage('configure', 'success', ['msg'=>'Tables updated: '.implode(', ', $tables_do)]);
				}
				if ($tables_error!==[]) {
					foreach ($tables_error as $key=>$error) {
						\osWFrame\Core\MessageStack::addMessage('configure', 'danger', ['msg'=>'Tables with error: '.$error.' - Error: '.$db_error[$key]]);
					}
				}
			} else {
				\osWFrame\Core\MessageStack::addMessage('configure', 'info', ['msg'=>'Database-tables: nothing to do.']);
			}
		} else {
			\osWFrame\Core\MessageStack::addMessage('configure', 'danger', ['msg'=>'Database-tables: creation/update was skipped (connection error).']);
		}
	} else {
		\osWFrame\Core\MessageStack::addMessage('configure', 'danger', ['msg'=>'Database-tables: creation/update was skipped (there is no database configured).']);
	}
}

if (($position=='run')&&(isset($_POST['prev']))&&($_POST['prev']=='prev')) {
	\osWFrame\Core\MessageStack::addMessage('configure', 'info', ['msg'=>'Database-tables: creation/update was skipped (go to previous page).']);
}

?>