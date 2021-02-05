<?php

if ( ($config_data['default_type']=='password')&&(strlen($this->data['values_post'][$config_element]['value'])==0)&&( (isset($this->data['values_json'][$config_element]))&&(strlen($this->data['values_json'][$config_element])>0)  ) ) {

} elseif (strlen($this->data['values_post'][$config_element]['value'])<$config_data['valid_min_length']) {
	$this->data['error_elements'][$config_element]=$config_data['default_name'].' is too short';
} else if (strlen($this->data['values_post'][$config_element]['value'])>$config_data['valid_max_length']) {
	$this->data['error_elements'][$config_element]=$config_data['default_name'].' is too long';
}


?>