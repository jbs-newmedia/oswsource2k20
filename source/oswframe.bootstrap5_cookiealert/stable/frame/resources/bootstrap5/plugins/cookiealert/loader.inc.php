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

$version='1.0.0';
$dir=strtolower($this->getClassName().DIRECTORY_SEPARATOR.$plugin_name);
$name=$plugin_name.DIRECTORY_SEPARATOR.$version.'.resource';
if (Resource::existsResource($this->getClassName(), $name)!==true) {
	$files=['js'.DIRECTORY_SEPARATOR.'cookiealert.js', 'css'.DIRECTORY_SEPARATOR.'cookiealert.css'];
	Resource::copyResourcePath('frame'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'bootstrap5'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$plugin_name.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR, $dir.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR, $files);
	Resource::writeResource($this->getClassName(), $name, time());
}

$path=Resource::getRelDir().$dir.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR;

$cssfiles=[$path.'css'.DIRECTORY_SEPARATOR.'cookiealert.css'];
$jsfiles=[$path.'js'.DIRECTORY_SEPARATOR.'cookiealert.js'];

$this->addTemplateCSSFiles('head', $cssfiles);
$this->addTemplateJSFiles('body', $jsfiles);

?>