<?php

$this->settings=['page_title'=>'Datebase Settings'];

$this->fields['database_server']=['default_name'=>'Server', 'default_type'=>'text', 'default_value'=>'localhost', 'valid_type'=>'string', 'valid_min_length'=>2, 'valid_max_length'=>32, 'configure_write'=>true];

$this->fields['database_port']=['default_name'=>'Port', 'default_type'=>'text', 'default_value'=>3306, 'valid_type'=>'integer', 'valid_min_length'=>2, 'valid_max_length'=>5, 'configure_write'=>true];

$this->fields['database_username']=['default_name'=>'Username', 'default_type'=>'text', 'default_value'=>'', 'valid_type'=>'string', 'valid_min_length'=>2, 'valid_max_length'=>32, 'configure_write'=>true];

$this->fields['database_password']=['default_name'=>'Password', 'default_type'=>'text', 'default_value'=>'', 'valid_type'=>'string', 'valid_min_length'=>0, 'valid_max_length'=>32, 'configure_write'=>true];

$this->fields['database_db']=['default_name'=>'Database', 'default_type'=>'text', 'default_value'=>'', 'valid_type'=>'string', 'valid_min_length'=>2, 'valid_max_length'=>32, 'configure_write'=>true];

$this->fields['database_prefix']=['default_name'=>'Prefix', 'default_type'=>'text', 'default_value'=>'osw_', 'valid_type'=>'string', 'valid_min_length'=>0, 'valid_max_length'=>32, 'configure_write'=>true];

$this->fields['database_engine']=['default_name'=>'Engine', 'default_type'=>'select', 'default_select'=>['InnoDB'=>'InnoDB', 'MyISAM'=>'MyISAM'], 'default_value'=>'InnoDB', 'valid_type'=>'string', 'valid_min_length'=>6, 'valid_max_length'=>6, 'configure_write'=>true];

$this->fields['database_character']=['default_name'=>'Character', 'default_type'=>'select', 'default_select'=>['utf8'=>'utf8', 'utf8mb4'=>'utf8mb4'], 'default_value'=>'utf8mb4', 'valid_type'=>'string', 'valid_min_length'=>2, 'valid_max_length'=>32, 'configure_write'=>true];

$this->fields['database_collation']=['default_name'=>'Collation', 'default_type'=>'select', 'default_select'=>['utf8_general_ci'=>'utf8_general_ci', 'utf8mb4_general_ci'=>'utf8mb4_general_ci'], 'default_value'=>'utf8mb4_general_ci', 'valid_type'=>'string', 'valid_min_length'=>2, 'valid_max_length'=>32, 'configure_write'=>true];

$this->fields['database']=['default_name'=>'Database', 'default_type'=>'function', 'valid_function'=>'check_database'];

?>