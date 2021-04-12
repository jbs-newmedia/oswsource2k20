<?php

$url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$matches=[];
if (($_SERVER['HTTP_HOST']=='127.0.0.1')||($_SERVER['HTTP_HOST']=='localhost')) {
	$matches['d']='localhost';
	$matches['tld']='';
} elseif (substr_count($_SERVER['HTTP_HOST'], '.')==1) {
	preg_match('/^(?P<d>.+)\.(?P<tld>.+?)$/', $_SERVER['HTTP_HOST'], $matches);
} else {
	preg_match('/^(?P<sd>.+)\.(?P<d>.+?)\.(?P<tld>.+?)$/', $_SERVER['HTTP_HOST'], $matches);
}

$subdomain=(isset($matches['sd']))?$matches['sd']:'';
$domain=(isset($matches['d']))?$matches['d']:'';
$tld=(isset($matches['tld']))?$matches['tld']:'';
if (strlen($tld)>0) {
	$domain.='.'.$tld;
}

$path=substr(parse_url($url, PHP_URL_PATH), 1, -1);
$path=explode('/', $path);
if ($path[0]=='oswtools') {
	$path='';
} else {
	$path=$path[0];
}

$default_module='';
$ar_default_module=[];
$dir_list=scandir(\osWFrame\Core\Settings::getStringVar('settings_framepath').'modules'.DIRECTORY_SEPARATOR);
foreach ($dir_list as $dir) {
	if ((file_exists(\osWFrame\Core\Settings::getStringVar('settings_framepath').'modules'.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.'tpl'.DIRECTORY_SEPARATOR.'index.tpl.php'))||(file_exists(\osWFrame\Core\Settings::getStringVar('settings_framepath').'modules'.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'header.inc.php'))) {
		$ar_default_module[$dir]=$dir;
		if (($default_module=='')&&(substr($dir, 0, 1)!='_')) {
			$default_module=$dir;
		}
	}
}

$ar_default_language=[];
$ar_default_language['de_DE']='German (Default)';
$ar_default_language['en_US']='English';
$default_language='de_De';

$ar_locale=[];
ob_start();
system('locale -a');
$str=ob_get_contents();
if (strlen($str)) {
	ob_end_clean();
	$ar_str=explode("\n", $str);
	foreach ($ar_str as $str) {
		if (strpos($str, 'utf8')) {
			$ar_locale[$str]=$str;
		}
	}
} else {
	$ar_str=[];
	$ar_str['de_DE.utf8']='de_DE.utf8';
	$ar_str['en_GB.utf8']='en_GB.utf8';
	$ar_str['en_US.utf8']='en_US.utf8';
	$ar_str['deu_deu']='deu_deu'; // win
	$ar_str['gbr_gbr']='gbr_gbr'; // win
	$ar_str['usa_usa']='usa_usa'; // win
	foreach ($ar_str as $str) {
		if (setlocale(LC_ALL, $str)!==false) {
			$ar_locale[$str]=$str;
		}
	}
}

$ar_timezone=[];
$tzlist=DateTimeZone::listIdentifiers(DateTimeZone::ALL);
foreach ($tzlist as $timezone) {
	$ar_timezone[$timezone]=$timezone;
}

$this->settings=['page_title'=>'Project Settings'];

$this->fields['project_name']=['default_name'=>'Projectname', 'default_type'=>'text', 'default_value'=>'osWFrame Standalone', 'valid_type'=>'string', 'valid_min_length'=>2, 'valid_max_length'=>32, 'configure_write'=>true];

$this->fields['project_subdomain']=['default_name'=>'Subdomain', 'default_type'=>'text', 'default_value'=>$subdomain, 'valid_type'=>'string', 'valid_min_length'=>0, 'valid_max_length'=>32, 'configure_write'=>true];

$this->fields['project_domain']=['default_name'=>'Domain', 'default_type'=>'text', 'default_value'=>$domain, 'valid_type'=>'string', 'valid_min_length'=>4, 'valid_max_length'=>32, 'valid_function'=>'check_domain', 'configure_write'=>true];

$this->fields['project_path']=['default_name'=>'Path', 'default_type'=>'text', 'default_value'=>$path, 'valid_type'=>'string', 'valid_min_length'=>0, 'valid_max_length'=>32, 'configure_write'=>true];

$this->fields['settings_ssl']=['default_name'=>'SSL', 'default_type'=>'select', 'default_value'=>0, 'default_select'=>[0=>'No', 1=>'Yes'], 'valid_type'=>'boolean', 'configure_write'=>true, 'valid_min_length'=>1, 'valid_max_length'=>1];

$this->fields['project_email']=['default_name'=>'E-Mail (Contact)', 'default_type'=>'text', 'default_value'=>'info@'.$domain, 'valid_type'=>'string', 'valid_min_length'=>6, 'valid_max_length'=>32, 'valid_function'=>'check_email', 'configure_write'=>true];

$this->fields['project_email_system']=['default_name'=>'E-Mail (Admin)', 'default_type'=>'text', 'default_value'=>'admin@'.$domain, 'valid_type'=>'string', 'valid_min_length'=>6, 'valid_max_length'=>32, 'valid_function'=>'check_email', 'configure_write'=>true];

$this->fields['project_default_module']=['default_name'=>'Defaultmodule', 'default_type'=>'select', 'default_value'=>$default_module, 'default_select'=>$ar_default_module, 'valid_type'=>'string', 'valid_min_length'=>1, 'valid_max_length'=>32, 'configure_write'=>true];

$this->fields['project_default_language']=['default_name'=>'Defaultlanguage', 'default_type'=>'select', 'default_value'=>$default_language, 'default_select'=>$ar_default_language, 'valid_type'=>'string', 'valid_min_length'=>2, 'valid_max_length'=>7, 'configure_write'=>true];

$this->fields['project_locale']=['default_name'=>'Locale', 'default_type'=>'select', 'default_value'=>'de_DE.utf8', 'default_select'=>$ar_locale, 'valid_type'=>'string', 'valid_min_length'=>3, 'valid_max_length'=>32, 'configure_write'=>true];

$this->fields['project_timezone']=['default_name'=>'Timezone', 'default_type'=>'select', 'default_value'=>'Europe/Paris', 'default_select'=>$ar_timezone, 'valid_type'=>'string', 'valid_min_length'=>6, 'valid_max_length'=>32, 'configure_write'=>true];

?>