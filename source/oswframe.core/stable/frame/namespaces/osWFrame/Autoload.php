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
	static $oswframe_core_vendor_namespace_path=null;
	static $oswframe_core_vendor_class_path=null;

	if ($oswframe_core_namespace_path===null) {
		$oswframe_core_namespace_path=realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..').DIRECTORY_SEPARATOR;
	}

	$filename=str_replace('\\', DIRECTORY_SEPARATOR, $className).'.php';
	$full_path_namespace=$oswframe_core_namespace_path.$filename;

	if (file_exists($full_path_namespace)) {
		require_once $full_path_namespace;
	} else {
		if ($oswframe_core_vendor_namespace_path===null) {
			$oswframe_core_vendor_namespace_path=realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..').DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'namespaces'.DIRECTORY_SEPARATOR;
		}

		if ($oswframe_core_vendor_class_path===null) {
			$oswframe_core_vendor_class_path=realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..').DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR;
		}

		$fa=explode(DIRECTORY_SEPARATOR, $filename);
		$ca=explode('\\', $className);
		switch (count($fa)) {
			case 1:
				$filename_namespace='';
			case 2:
				$filename_namespace=$fa[0].DIRECTORY_SEPARATOR.$ca[1].DIRECTORY_SEPARATOR.(string)\osWFrame\Core\Settings::getStringVar('vendor_namespace_'.strtolower($fa[0]).'_'.strtolower($ca[1]).'_version').DIRECTORY_SEPARATOR.$fa[1];
				break;
			default:
				$fa2=$fa;
				unset($fa2[0]);
				unset($fa2[1]);
				$filename_namespace=$fa[0].DIRECTORY_SEPARATOR.$ca[1].DIRECTORY_SEPARATOR.(string)\osWFrame\Core\Settings::getStringVar('vendor_namespace_'.strtolower($fa[0]).'_'.strtolower($ca[1]).'_version').DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR, $fa2);
				break;
		}
		$filename_class=$ca[0].DIRECTORY_SEPARATOR.(string)\osWFrame\Core\Settings::getStringVar('vendor_class_'.strtolower($ca[0]).'_version').DIRECTORY_SEPARATOR.$fa[0];

		$full_path_vendor_namespace=$oswframe_core_vendor_namespace_path.$filename_namespace;
		$full_path_vendor_class=$oswframe_core_vendor_class_path.$filename_class;

		if (file_exists($full_path_vendor_namespace)) {
			require_once $full_path_vendor_namespace;
		} elseif (file_exists($full_path_vendor_class)) {
			require_once $full_path_vendor_class;
		} elseif (file_exists(strtolower($full_path_vendor_class))) {
			require_once strtolower($full_path_vendor_class);
		}
	}
});
?>