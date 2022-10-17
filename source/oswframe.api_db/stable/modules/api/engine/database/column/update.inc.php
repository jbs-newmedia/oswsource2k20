<?php

$DBStruct=new \osWFrame\Api\Database\DBStruct();
$DBStruct->updateColumn(\osWFrame\Core\Settings::catchStringValue('table'), \osWFrame\Core\Settings::catchStringValue('name'), \osWFrame\Core\Settings::catchStringValue('type'), \osWFrame\Core\Settings::catchIntValue('length'), \osWFrame\Core\Settings::catchIntValue('position'), \osWFrame\Core\Settings::catchStringValue('collation'), \osWFrame\Core\Settings::catchStringValue('options'), \osWFrame\Core\Settings::catchIntValue('setnull'), \osWFrame\Core\Settings::catchIntValue('autoincrement'));

$osW_Result->setError($DBStruct->getError());
$osW_Result->setErrorMessage($DBStruct->getErrorMessage());
$osW_Result->setSuccessMessage($DBStruct->getSuccessMessage());

?>



