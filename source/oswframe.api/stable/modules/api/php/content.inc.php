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
$file_header=$dir.$osW_Controller->getApi().DIRECTORY_SEPARATOR.'header.inc.php';

$osW_Result=new \osWFrame\Api\Result();

if (file_exists($file)) {
	$api_go=true;
	if (file_exists($file_header)) {
		require_once $file_header;
	}
	if ($api_go===true) {
		require_once $file;
	}
} else {
	$osW_Result->setError(true);
	$osW_Result->setErrorMessage('Api not found. (- API: '.$osW_Controller->getApi('undefined').' -|- SECTION: '.$osW_Controller->getSection('undefined').' -|- FUNCTION: '.$osW_Controller->getFunction('undefined').' -)');
}

?>