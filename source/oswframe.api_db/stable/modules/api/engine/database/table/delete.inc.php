<?php

$DBStruct=new \osWFrame\Api\Database\DBStruct();
$DBStruct->deleteTable(\osWFrame\Core\Settings::catchStringValue('name'));

$osW_Result->setError($DBStruct->getError());
$osW_Result->setErrorMessage($DBStruct->getErrorMessage());
$osW_Result->setSuccessMessage($DBStruct->getSuccessMessage());

?>