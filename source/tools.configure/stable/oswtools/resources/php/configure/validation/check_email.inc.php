<?php

$email=explode('@', $this->data['values_post'][$config_element]['value']);

if ((!isset($email[1]))||(!$email[1]=='localhost')) {
	if (filter_var($this->data['values_post'][$config_element]['value'], FILTER_VALIDATE_EMAIL)===false) {
		$this->data['error'][$config_element]=$config_data['default_name'].' not correct';
	}
}

?>