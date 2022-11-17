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

$osW_Scripts=new \osWFrame\Core\Scripts();
if ($osW_Scripts->checkGlobalLock()===true) {
	$script=basename(\osWFrame\Core\Settings::catchStringValue('script', '', 'gp'));

	$search_dirs=[];

	$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_default_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'scripts_header.inc.php';
	if (file_exists($file)) {
		require_once $file;
	}

	$search_dirs[]='actions';

	$found=false;
	foreach ($search_dirs as $_dir) {
		if ($found===false) {
			$dir=realpath(\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_default_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.$_dir);
			$file=$dir.'/'.$script.'.inc.php';

			if ((file_exists($file))&&(dirname(realpath($file))==$dir)) {
				require_once $file;
				$found=true;
			}
		}
	}

	$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_default_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'scripts_footer.inc.php';
	if (file_exists($file)) {
		require_once $file;
	}

	if ($found!==true) {
		echo 'script "'.$script.'" not found.';
	}
} else {
	echo 'script blocked by global-lock.';
}

?>