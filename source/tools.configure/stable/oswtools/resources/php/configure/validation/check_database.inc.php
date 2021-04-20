<?php

\osWFrame\Core\DB::addConnectionMYSQL($this->values_post['database_server']['value'], $this->values_post['database_username']['value'], $this->values_post['database_password']['value'], $this->values_post['database_db']['value']);
if (\osWFrame\Core\DB::connect()!==true) {
	$this->getForm()->addErrorMessage('conf_database_server', 'Connection failed');
}

?>