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

$version='4.1.1';

if (!isset($options['min'])) {
	$options['min']=true;
}

$dir=strtolower($this->getClassName().DIRECTORY_SEPARATOR.$lib_name);
$name=$lib_name.DIRECTORY_SEPARATOR.$version.'.resource';
if (Resource::existsResource($this->getClassName(), $name)!==true) {
	Resource::copyResourcePath('frame'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'jslib'.DIRECTORY_SEPARATOR.$lib_name.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR, $dir.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR);
	Resource::writeResource($this->getClassName(), $name, time());
}

$path=Resource::getRelDir().$dir.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR;

if ($options['min']===true) {
	$cssfiles=[$path.'css'.DIRECTORY_SEPARATOR.'animate.min.css'];
} else {
	$cssfiles=[$path.'css'.DIRECTORY_SEPARATOR.'animate.css'];
}

$this->addTemplateCSSFiles('head', $cssfiles);

?>