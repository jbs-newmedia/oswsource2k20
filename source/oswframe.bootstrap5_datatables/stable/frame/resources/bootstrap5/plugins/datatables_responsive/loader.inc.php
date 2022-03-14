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

if (!isset($options['min'])) {
	$options['min']=true;
}

$version='2.2.7';
$dir=strtolower($this->getClassName().DIRECTORY_SEPARATOR.$plugin_name);
$name=$plugin_name.DIRECTORY_SEPARATOR.$version.'.resource';
if (Resource::existsResource($this->getClassName(), $name)!==true) {
	$files=['js'.DIRECTORY_SEPARATOR.'dataTables.responsive.js', 'js'.DIRECTORY_SEPARATOR.'dataTables.responsive.min.js', 'js'.DIRECTORY_SEPARATOR.'responsive.bootstrap5.js', 'js'.DIRECTORY_SEPARATOR.'responsive.bootstrap5.min.js', 'css'.DIRECTORY_SEPARATOR.'responsive.bootstrap5.css', 'css'.DIRECTORY_SEPARATOR.'responsive.bootstrap5.min.css'];
	Resource::copyResourcePath('frame'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'jquery3'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$plugin_name.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR, $dir.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR, $files);
	Resource::writeResource($this->getClassName(), $name, time());
}

$path=Resource::getRelDir().$dir.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR;

if ($options['min']===true) {
	$jsfiles=[$path.'js'.DIRECTORY_SEPARATOR.'dataTables.responsive.min.js', $path.'js'.DIRECTORY_SEPARATOR.'responsive.bootstrap5.min.js'];
	$cssfiles=[$path.'css'.DIRECTORY_SEPARATOR.'responsive.bootstrap5.min.css'];
}
else {
	$jsfiles=[$path.'js'.DIRECTORY_SEPARATOR.'dataTables.responsive.js', $path.'js'.DIRECTORY_SEPARATOR.'responsive.bootstrap5.js'];
	$cssfiles=[$path.'css'.DIRECTORY_SEPARATOR.'responsive.bootstrap5.css'];
}

$this->addTemplateJSFiles('head', $jsfiles);
$this->addTemplateCSSFiles('head', $cssfiles);

?>