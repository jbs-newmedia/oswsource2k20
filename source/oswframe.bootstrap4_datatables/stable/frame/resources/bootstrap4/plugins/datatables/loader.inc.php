<?php

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

namespace osWFrame\Core;

if (!isset($options['min'])) {
	$options['min']=true;
}

$version='1.10.24';
$dir=strtolower($this->getClassName().DIRECTORY_SEPARATOR.$plugin_name);
$name=$plugin_name.DIRECTORY_SEPARATOR.$version.'.resource';
if (Resource::existsResource($this->getClassName(), $name)!==true) {
	$files=['js'.DIRECTORY_SEPARATOR.'jquery.dataTables.js', 'js'.DIRECTORY_SEPARATOR.'dataTables.bootstrap4.js', 'css'.DIRECTORY_SEPARATOR.'dataTables.bootstrap4.css', 'js'.DIRECTORY_SEPARATOR.'jquery.dataTables.min.js', 'js'.DIRECTORY_SEPARATOR.'dataTables.bootstrap4.min.js', 'css'.DIRECTORY_SEPARATOR.'dataTables.bootstrap4.min.css'];
	Resource::copyResourcePath('frame'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'jquery3'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$plugin_name.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR, $dir.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR, $files);
	Resource::writeResource($this->getClassName(), $name, time());
}

$path=Resource::getRelDir().$dir.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR;

if ($options['min']===true) {
	$jsfiles=[$path.'js'.DIRECTORY_SEPARATOR.'jquery.dataTables.min.js', $path.'js'.DIRECTORY_SEPARATOR.'dataTables.bootstrap4.min.js'];
	$cssfiles=[$path.'css'.DIRECTORY_SEPARATOR.'dataTables.bootstrap4.min.css'];
}
else {
	$jsfiles=[$path.'js'.DIRECTORY_SEPARATOR.'jquery.dataTables.js', $path.'js'.DIRECTORY_SEPARATOR.'dataTables.bootstrap4.js'];
	$cssfiles=[$path.'css'.DIRECTORY_SEPARATOR.'dataTables.bootstrap4.css'];
}

$this->addTemplateJSFiles('head', $jsfiles);
$this->addTemplateCSSFiles('head', $cssfiles);

?>