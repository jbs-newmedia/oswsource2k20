<?php

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license MIT License
 */

$osW_Controller=new \osWFrame\Api\Controller(\osWFrame\Core\Settings::catchStringValue('api', '', 'gp'), \osWFrame\Core\Settings::catchStringValue('section', '', 'gp'), \osWFrame\Core\Settings::catchStringValue('function', '', 'gp'));

$dir=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_default_module').DIRECTORY_SEPARATOR.'engine'.DIRECTORY_SEPARATOR;
$file=$dir.$osW_Controller->getApi().DIRECTORY_SEPARATOR.$osW_Controller->getSection().DIRECTORY_SEPARATOR.$osW_Controller->getFunction().'.inc.php';

$osW_Result=new \osWFrame\Api\Result();

if (file_exists($file)) {
	require_once $file;
} else {
	$osW_Result->setError(true);
	$osW_Result->setErrorMessage('Api not found. (- API: '.$osW_Controller->getApi('undefined').' -|- SECTION: '.$osW_Controller->getSection('undefined').' -|- FUNCTION: '.$osW_Controller->getFunction('undefined').' -)');
}

?>