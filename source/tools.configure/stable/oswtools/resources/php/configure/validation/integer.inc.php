<?php

if (($config_data['default_type']=='password')&&(strlen($this->values_post[$config_element]['value'])==0)&&((isset($this->data['values_json'][$config_element]))&&(strlen($this->data['values_json'][$config_element])>0))) {

} elseif (strlen($this->values_post[$config_element]['value'])<$config_data['valid_min_length']) {
	$this->getForm()->addErrorMessage($config_element, $config_data['default_name'].' is too short');
} elseif (strlen($this->values_post[$config_element]['value'])>$config_data['valid_max_length']) {
	$this->getForm()->addErrorMessage($config_element, $config_data['default_name'].' is too long');
}

?>