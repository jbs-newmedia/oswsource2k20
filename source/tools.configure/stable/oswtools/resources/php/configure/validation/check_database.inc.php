<?php

$link = @mysqli_connect($this->data['values_post']['database_server']['value'], $this->data['values_post']['database_username']['value'], $this->data['values_post']['database_password']['value']);
if (!$link) {
	$this->data['error'][$config_element]=$config_data['default_name'].' error: '. mysqli_connect_error();
}

if ($link) {
	$db_selected = @mysqli_select_db($link, $this->data['values_post']['database_db']['value']);
	if (!$db_selected) {
		$this->data['error'][$config_element]=$config_data['default_name'].' error: '. mysqli_connect_error();
	}
}

?>