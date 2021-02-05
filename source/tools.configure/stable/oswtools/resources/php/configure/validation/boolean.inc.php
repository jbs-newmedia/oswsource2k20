<?php


if (strlen($this->data['values_post'][$config_element]['value'])<$config_data['valid_min_length']) {
	$this->data['error_elements'][$config_element]=$config_data['default_name'].' is too short';
} else if (strlen($this->data['values_post'][$config_element]['value'])>$config_data['valid_max_length']) {
	$this->data['error_elements'][$config_element]=$config_data['default_name'].' is too long';
}


?>