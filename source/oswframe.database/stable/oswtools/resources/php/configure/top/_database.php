<?php

$this->data['settings']=array();

$this->data['settings']['data']=array(
	'page_title'=>'Datebase Settings',
);

$this->data['settings']['fields']['database_server']=array(
	'default_name'=>'Server',
	'default_type'=>'text',
	'default_value'=>'localhost',
	'valid_type'=>'string',
	'valid_min_length'=>2,
	'valid_max_length'=>32,
	'configure_write'=>true,
);

$this->data['settings']['fields']['database_username']=array(
	'default_name'=>'Username',
	'default_type'=>'text',
	'default_value'=>'',
	'valid_type'=>'string',
	'valid_min_length'=>2,
	'valid_max_length'=>32,
	'configure_write'=>true,
);

$this->data['settings']['fields']['database_password']=array(
	'default_name'=>'Password',
	'default_type'=>'text',
	'default_value'=>'',
	'valid_type'=>'string',
	'valid_min_length'=>0,
	'valid_max_length'=>32,
	'configure_write'=>true,
);

$this->data['settings']['fields']['database_db']=array(
	'default_name'=>'Database',
	'default_type'=>'text',
	'default_value'=>'',
	'valid_type'=>'string',
	'valid_min_length'=>2,
	'valid_max_length'=>32,
	'configure_write'=>true,
);

$this->data['settings']['fields']['database_prefix']=array(
	'default_name'=>'Prefix',
	'default_type'=>'text',
	'default_value'=>'osw_',
	'valid_type'=>'string',
	'valid_min_length'=>0,
	'valid_max_length'=>32,
	'configure_write'=>true,
);

$this->data['settings']['fields']['database_engine']=array(
	'default_name'=>'Engine',
	'default_type'=>'select',
	'default_select'=>array('InnoDB'=>'InnoDB', 'MyISAM'=>'MyISAM'),
	'default_value'=>'InnoDB',
	'valid_type'=>'string',
	'valid_min_length'=>6,
	'valid_max_length'=>6,
	'configure_write'=>true,
);

$this->data['settings']['fields']['database_character']=array(
	'default_name'=>'Character',
	'default_type'=>'select',
	'default_select'=>array('utf8'=>'utf8', 'utf8mb4'=>'utf8mb4'),
	'default_value'=>'utf8mb4',
	'valid_type'=>'string',
	'valid_min_length'=>2,
	'valid_max_length'=>32,
	'configure_write'=>true,
);

$this->data['settings']['fields']['database_collation']=array(
	'default_name'=>'Collation',
	'default_type'=>'select',
	'default_select'=>array('utf8_general_ci'=>'utf8_general_ci', 'utf8mb4_general_ci'=>'utf8mb4_general_ci'),
	'default_value'=>'utf8mb4_general_ci',
	'valid_type'=>'string',
	'valid_min_length'=>2,
	'valid_max_length'=>32,
	'configure_write'=>true,
);

$this->data['settings']['fields']['database']=array(
	'default_name'=>'Database',
	'default_type'=>'function',
	'valid_function'=>'check_database',
);

?>