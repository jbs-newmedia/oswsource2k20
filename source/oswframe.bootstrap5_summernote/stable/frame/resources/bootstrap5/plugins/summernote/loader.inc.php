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

if (!isset($options['language'])) {
	$options['language']=Language::getCurrentLanguage();
}

$version='0.8.18';
$dir=strtolower($this->getClassName().DIRECTORY_SEPARATOR.$plugin_name);
$name=$plugin_name.DIRECTORY_SEPARATOR.$version.'.resource';
if (Resource::existsResource($this->getClassName(), $name)!==true) {
	Resource::copyResourcePath('frame'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'jquery3'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$plugin_name.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR, $dir.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR);

	$file=Resource::getRelDir().$dir.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'summernote-bs5.css';
	$content=file_get_contents($file);
	$content=str_replace('url(font/', 'url(/'.Resource::getRelDir().$dir.'/'.$version.'/font/', $content);
	file_put_contents($file, $content);

	$file=Resource::getRelDir().$dir.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'summernote-bs5.min.css';
	$content=file_get_contents($file);
	$content=str_replace('url(font/', 'url(/'.Resource::getRelDir().$dir.'/'.$version.'/font/', $content);
	file_put_contents($file, $content);

	Resource::writeResource($this->getClassName(), $name, time());
}

$path=Resource::getRelDir().$dir.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR;

if ($options['min']===true) {
	$jsfiles=[$path.'js'.DIRECTORY_SEPARATOR.'summernote-bs5.min.js'];
	$cssfiles=[$path.'css'.DIRECTORY_SEPARATOR.'summernote-bs5.css'];
	$filename=$path.'js'.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.'summernote-'.str_replace('_', '-', $options['language']).'.min.js';
} else {
	$jsfiles=[$path.'js'.DIRECTORY_SEPARATOR.'summernote-bs5.js'];
	$cssfiles=[$path.'css'.DIRECTORY_SEPARATOR.'summernote-bs5.css'];
	$filename=$path.'js'.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.'summernote-'.str_replace('_', '-', $options['language']).'.js';
}

if (file_exists(Settings::getStringVar('settings_abspath').$filename)===true) {
	$jsfiles[]=$filename;
}

$this->addTemplateJSFiles('head', $jsfiles);
$this->addTemplateCSSFiles('head', $cssfiles);

?>