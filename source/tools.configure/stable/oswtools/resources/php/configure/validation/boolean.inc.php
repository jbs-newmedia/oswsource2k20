<?php


if (strlen($this->values_post[$config_element]['value'])<$config_data['valid_min_length']) {
	$this->getForm()->addErrorMessage($config_element, $config_data['default_name'].' is too short');
} else if (strlen($this->values_post[$config_element]['value'])>$config_data['valid_max_length']) {
	$this->getForm()->addErrorMessage($config_element, $config_data['default_name'].' is too long');
}


?>