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

if ((!isset($_FILES['upload']))&&($_FILES['upload']['error']!=0)) {
	\osWFrame\Core\Network::dieJSON(['error'=>['message'=>'Hochladen fehlgeschlagen!']]);
}

$var=\osWFrame\Core\Settings::catchStringGetValue('var');
$conf=\osWFrame\Core\Settings::catchArraySessionValue('ck5editor_'.$var);

if (!isset($conf['file_dir'])) {
	$conf['file_dir']='data'.DIRECTORY_SEPARATOR;
}
if (!isset($conf['file_name'])) {
	$conf['file_name']='shared_sha1';
	#$conf['file_name']='original';
}

$getimagesize=getimagesize($_FILES['upload']['tmp_name']);
if ($getimagesize===false) {
	\osWFrame\Core\Network::dieJSON(['error'=>['message'=>'Hochladen fehlgeschlagen!']]);
}
$filesize=filesize($_FILES['upload']['tmp_name']);
if ($filesize===false) {
	\osWFrame\Core\Network::dieJSON(['error'=>['message'=>'Hochladen fehlgeschlagen!']]);
}
$pathinfo=pathinfo($_FILES['upload']['name']);
if (is_array($pathinfo)!==true) {
	\osWFrame\Core\Network::dieJSON(['error'=>['message'=>'Hochladen fehlgeschlagen!']]);
}

if (isset($conf['file_types'])) {
	$finfo=finfo_open(FILEINFO_MIME_TYPE);
	if (!in_array(finfo_file($finfo, $_FILES['upload']['tmp_name']), $conf['file_types'])) {
		\osWFrame\Core\Network::dieJSON(['error'=>['message'=>'Dateityp nicht erlaubt. Nur ('.implode(', ', $conf['file_types']).') erlaubt.']]);
	}
}

if (isset($conf['file_extensions'])) {
	if (!in_array(strtolower($pathinfo['extension']), $conf['file_extensions'])) {
		\osWFrame\Core\Network::dieJSON(['error'=>['message'=>'Dateiendung nicht erlaubt. Nur ('.implode(', ', $conf['file_extensions']).') erlaubt.']]);
	}
}

if (isset($conf['file_size_min'])) {
	if ($filesize<$conf['file_size_min']) {
		\osWFrame\Core\Network::dieJSON(['error'=>['message'=>'Datei zu klein. Mindestens '.$conf['file_size_min'].' Bytes.']]);
	}
}

if (isset($conf['file_size_max'])) {
	if ($filesize>$conf['file_size_max']) {
		\osWFrame\Core\Network::dieJSON(['error'=>['message'=>'Datei zu groß. Maximal '.$conf['file_size_max'].' Bytes.']]);
	}
}

if (isset($conf['file_width_min'])) {
	if ($getimagesize[0]<$conf['file_width_min']) {
		\osWFrame\Core\Network::dieJSON(['error'=>['message'=>'Bild zu klein. Mindestens '.$conf['file_width_min'].' Pixel.']]);
	}
}

if (isset($conf['file_width_max'])) {
	if ($getimagesize[0]>$conf['file_width_max']) {
		\osWFrame\Core\Network::dieJSON(['error'=>['message'=>'Bild zu groß. Maximal '.$conf['file_width_max'].' Pixel.']]);
	}
}

if (isset($conf['file_height_min'])) {
	if ($getimagesize[1]<$conf['file_height_min']) {
		\osWFrame\Core\Network::dieJSON(['error'=>['message'=>'Bild zu klein. Mindestens '.$conf['file_height_min'].' Pixel.']]);
	}
}

if (isset($conf['file_height_max'])) {
	if ($getimagesize[1]>$conf['file_height_max']) {
		\osWFrame\Core\Network::dieJSON(['error'=>['message'=>'Bild zu groß. Maximal '.$conf['file_height_max'].' Pixel.']]);
	}
}

$dir=\osWFrame\Core\Settings::getStringVar('settings_abspath').$conf['file_dir'];
switch ($conf['file_name']) {
	case 'time+rand':
		$file_name=time().rand(100, 999).'.'.$pathinfo['extension'];
		break;
	case 'name_rand':
		$file_name=$pathinfo['filename'].'_'.rand(100, 999).'.'.$pathinfo['extension'];
		break;
	case 'original':
		$file_name=$_FILES['upload']['name'];
		break;
	case 'md5':
		$file_name=hash_file('md5', $_FILES['upload']['tmp_name']).'.'.$pathinfo['extension'];
		break;
	case 'sha1':
		$file_name=hash_file('sha1', $_FILES['upload']['tmp_name']).'.'.$pathinfo['extension'];
		break;
	case 'shared_md5':
		$file_name=hash_file('md5', $_FILES['upload']['tmp_name']).'.'.$pathinfo['extension'];
		$dir=str_replace('//', '/', \osWFrame\Core\Settings::getStringVar('settings_abspath').$conf['file_dir'].'/'.substr($file_name, 0, 2).'/'.substr($file_name, 2, 2).'/');
		break;
	case 'shared_sha1':
		$file_name=hash_file('sha1', $_FILES['upload']['tmp_name']).'.'.$pathinfo['extension'];
		$dir=str_replace('//', '/', \osWFrame\Core\Settings::getStringVar('settings_abspath').$conf['file_dir'].'/'.substr($file_name, 0, 2).'/'.substr($file_name, 2, 2).'/');
		break;
	default:
		$file_name=$conf['file_name'];
		break;
}

\osWFrame\Core\Filesystem::makeDir($dir);
move_uploaded_file($_FILES['upload']['tmp_name'], $dir.$file_name);
\osWFrame\Core\Filesystem::changeFilemode($dir.$file_name);

$path=str_replace([\osWFrame\Core\Settings::getStringVar('settings_abspath'), DIRECTORY_SEPARATOR], ['', '/'], $dir);

\osWFrame\Core\Network::dieJSON(['url'=>$path.$file_name]);

?>