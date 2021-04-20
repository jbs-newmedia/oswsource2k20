<?php

$this->values_post[$config_element]['value']=intval($this->values_post[$config_element]['value']);

if (($config_data['default_type']=='password')&&(strlen($this->values_post[$config_element]['value'])==0)&&((isset($this->data['values_json'][$config_element]))&&(strlen($this->data['values_json'][$config_element])>0))) {

} elseif ((isset($config_data['valid_min_length']))&&(strlen($this->values_post[$config_element]['value'])<$config_data['valid_min_length'])) {
	$this->getForm()->addErrorMessage('conf_'.$config_element, $config_data['default_name'].' is too short');
} elseif ((isset($config_data['valid_max_length']))&&(strlen($this->values_post[$config_element]['value'])>$config_data['valid_max_length'])) {
	$this->getForm()->addErrorMessage('conf_'.$config_element, $config_data['default_name'].' is too long');
} elseif ((isset($config_data['valid_min_value']))&&($this->values_post[$config_element]['value']<$config_data['valid_min_value'])) {
	$this->getForm()->addErrorMessage('conf_'.$config_element, $config_data['default_name'].' is too small');
} elseif ((isset($config_data['valid_max_value']))&&($this->values_post[$config_element]['value']>$config_data['valid_max_value'])) {
	$this->getForm()->addErrorMessage('conf_'.$config_element, $config_data['default_name'].' is too big');
}

?>