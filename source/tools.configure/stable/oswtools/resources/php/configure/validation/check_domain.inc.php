<?php

$domain='http://';
if (strlen($this->data['values_post']['project_subdomain']['value'])) {
	$domain.=$this->data['values_post']['project_subdomain']['value'].'.';
}
$domain.=$this->data['values_post']['project_domain']['value'];
if (strlen($this->data['values_post']['project_path']['value'])) {
	$domain.='/'.$this->data['values_post']['project_path']['value'].'/';
}

if (filter_var($domain, FILTER_VALIDATE_URL)===false) {
	$this->data['error'][$config_element]=$config_data['default_name'].' not correct';
}

?>