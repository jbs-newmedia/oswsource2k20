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

/**
 * Autoloader für Namespaces
 */
spl_autoload_register(function($className) {
	static $oswframe_core_namespace_path=null;

	if ($oswframe_core_namespace_path===null) {
		$oswframe_core_namespace_path=realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..').DIRECTORY_SEPARATOR;
	}

	$filename=str_replace('\\', DIRECTORY_SEPARATOR, $className).'.php';
	$fullpath=$oswframe_core_namespace_path.$filename;

	if (file_exists($fullpath)) {
		require_once $fullpath;
	}
});
?>