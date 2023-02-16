<?php

$DBStruct=new \osWFrame\Api\Database\DBStruct();
$DBStruct->updateTable(\osWFrame\Core\Settings::catchStringValue('name'), \osWFrame\Core\Settings::catchStringValue('storage_engine'), \osWFrame\Core\Settings::catchStringValue('collation'), \osWFrame\Core\Settings::catchStringValue('comment'));

$osW_Result->setError($DBStruct->getError());
$osW_Result->setErrorMessage($DBStruct->getErrorMessage());
$osW_Result->setSuccessMessage($DBStruct->getSuccessMessage());

?>