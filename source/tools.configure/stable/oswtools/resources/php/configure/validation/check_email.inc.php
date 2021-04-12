<?php

$email=explode('@', $this->values_post[$config_element]['value']);

if ((!isset($email[1]))||(!$email[1]=='localhost')) {
	if (filter_var($this->values_post[$config_element]['value'], FILTER_VALIDATE_EMAIL)===false) {
		$this->getForm()->addErrorMessage('conf_'.$config_element, $config_data['default_name'].' not correct');
	}
}

?>