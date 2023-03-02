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

namespace osWFrame\Core;

$version='1.14.0-beta3-oswframe-rc1';

if (!isset($options['min'])) {
	$options['min']=true;
}

if (!isset($options['language'])) {
	$options['language']=Language::getCurrentLanguage();
}

$dir=strtolower($this->getClassName().DIRECTORY_SEPARATOR.$plugin_name);
$name=$plugin_name.DIRECTORY_SEPARATOR.$version.'.resource';
if (Resource::existsResource($this->getClassName(), $name)!==true) {
	Resource::copyResourcePath('frame'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'bootstrap5'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$plugin_name.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR, $dir.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR);
	Resource::writeResource($this->getClassName(), $name, time());
}

$path=Resource::getRelDir().$dir.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR;

if ($options['min']===true) {
	$jsfiles=[$path.'js'.DIRECTORY_SEPARATOR.'bootstrap-select.min.js'];
	$cssfiles=[$path.'css'.DIRECTORY_SEPARATOR.'bootstrap-select.min.css'];
	$filename=$path.'js'.DIRECTORY_SEPARATOR.'i18n'.DIRECTORY_SEPARATOR.'defaults-'.$options['language'].'.min.js';
} else {
	$jsfiles=[$path.'js'.DIRECTORY_SEPARATOR.'bootstrap-select.js'];
	$cssfiles=[$path.'css'.DIRECTORY_SEPARATOR.'bootstrap-select.css'];
	$filename=$path.'js'.DIRECTORY_SEPARATOR.'i18n'.DIRECTORY_SEPARATOR.'defaults-'.$options['language'].'.js';
}

if (file_exists(Settings::getStringVar('settings_abspath').$filename)===true) {
	$jsfiles[]=$filename;
}

$this->addTemplateJSFiles('head', $jsfiles);
$this->addTemplateCSSFiles('head', $cssfiles);

?>