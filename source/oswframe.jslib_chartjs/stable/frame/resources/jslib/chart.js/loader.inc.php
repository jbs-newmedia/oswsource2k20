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

if (!isset($options['bundle'])) {
	$options['bundle']=true;
}

$version='2.9.4';
$dir=strtolower($this->getClassName().DIRECTORY_SEPARATOR.$lib_name);
$name=$lib_name.DIRECTORY_SEPARATOR.$version.'.resource';
if (Resource::existsResource($this->getClassName(), $name)!==true) {
	$files=['js'.DIRECTORY_SEPARATOR.'Chart.bundle.js', 'js'.DIRECTORY_SEPARATOR.'Chart.bundle.min.js', 'js'.DIRECTORY_SEPARATOR.'Chart.js', 'js'.DIRECTORY_SEPARATOR.'Chart.min.js', 'css'.DIRECTORY_SEPARATOR.'Chart.css', 'css'.DIRECTORY_SEPARATOR.'Chart.min.css'];
	Resource::copyResourcePath('frame'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'jslib'.DIRECTORY_SEPARATOR.$lib_name.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR, $dir.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR, $files);
	Resource::writeResource($this->getClassName(), $name, time());
}

$path=Resource::getRelDir().$dir.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR;

if ($options['min']===true) {
	if ($options['bundle']===true) {
		$jsfiles=[$path.'js'.DIRECTORY_SEPARATOR.'Chart.bundle.min.js'];
	} else {
		$jsfiles=[$path.'js'.DIRECTORY_SEPARATOR.'Chart.min.js'];
	}
	$cssfiles=[$path.'css'.DIRECTORY_SEPARATOR.'Chart.min.css'];
} else {
	if ($options['bundle']===true) {
		$jsfiles=[$path.'js'.DIRECTORY_SEPARATOR.'Chart.bundle.js'];
	} else {
		$jsfiles=[$path.'js'.DIRECTORY_SEPARATOR.'Chart.js'];
	}
	$cssfiles=[$path.'css'.DIRECTORY_SEPARATOR.'Chart.css'];
}

$this->addTemplateJSFiles('head', $jsfiles);
$this->addTemplateCSSFiles('head', $cssfiles);

?>