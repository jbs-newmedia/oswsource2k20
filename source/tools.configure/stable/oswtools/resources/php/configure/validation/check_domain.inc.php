<?php

$domain='http://';
if (strlen($this->values_post['project_subdomain']['value'])) {
	$domain.=$this->values_post['project_subdomain']['value'].'.';
}
$domain.=$this->values_post['project_domain']['value'];
if (strlen($this->values_post['project_path']['value'])) {
	$domain.='/'.$this->values_post['project_path']['value'].'/';
}

if (filter_var($domain, FILTER_VALIDATE_URL)===false) {
	$this->getForm()->addErrorMessage('conf_'.$config_element, $config_data['default_name'].' not correct');
}

?>