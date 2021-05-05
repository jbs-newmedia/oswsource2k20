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

$version='5.0.0';
$dir=strtolower($this->getClassName().DIRECTORY_SEPARATOR.$plugin_name);

$name=$plugin_name.DIRECTORY_SEPARATOR.$version.'.resource';
if (Resource::existsResource($this->getClassName(), $name)!==true) {
	$files=['js'.DIRECTORY_SEPARATOR.'jbs-admin-5.js', 'css'.DIRECTORY_SEPARATOR.'jbs-admin-5.css', 'css'.DIRECTORY_SEPARATOR.'nunito.css', 'font'.DIRECTORY_SEPARATOR.'nunito'.DIRECTORY_SEPARATOR.'nunito-v16-latin-regular.eot', 'font'.DIRECTORY_SEPARATOR.'nunito'.DIRECTORY_SEPARATOR.'nunito-v16-latin-regular.svg', 'font'.DIRECTORY_SEPARATOR.'nunito'.DIRECTORY_SEPARATOR.'nunito-v16-latin-regular.ttf', 'font'.DIRECTORY_SEPARATOR.'nunito'.DIRECTORY_SEPARATOR.'nunito-v16-latin-regular.woff', 'font'.DIRECTORY_SEPARATOR.'nunito'.DIRECTORY_SEPARATOR.'nunito-v16-latin-regular.woff2'];
	Resource::copyResourcePath('frame'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'bootstrap5'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$plugin_name.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR, $dir.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR, $files);
	$file=Resource::getRelDir().$dir.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'nunito.css';
	$content=file_get_contents($file);
	$content=str_replace('$osw_source_path$', DIRECTORY_SEPARATOR.Resource::getRelDir().$dir.DIRECTORY_SEPARATOR.$version, $content);
	file_put_contents($file, $content);
	Resource::writeResource($this->getClassName(), $name, time());
}

$path=Resource::getRelDir().$dir.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR;
$jsfiles=[$path.'js'.DIRECTORY_SEPARATOR.'jbs-admin-5.js'];
$cssfiles=[$path.'css'.DIRECTORY_SEPARATOR.'nunito.css', $path.'css'.DIRECTORY_SEPARATOR.'jbs-admin-5.css'];

$this->addTemplateJSFiles('head', $jsfiles);
$this->addTemplateCSSFiles('head', $cssfiles);

?>