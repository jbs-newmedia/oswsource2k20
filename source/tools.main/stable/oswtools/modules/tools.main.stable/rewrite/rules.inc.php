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

if (isset($user_parameters)) {
	$acceptable_user_parameters=array_merge(['action', 'doaction', \osWFrame\Core\Settings::getStringVar('session_name')], $user_parameters);
} else {
	$acceptable_user_parameters=['action', 'doaction', \osWFrame\Core\Settings::getStringVar('session_name')];
}

$acceptable_spider_parameters=[];

$go_default=false;

if ($rewrite_module===true) {
	$base_uri=\osWFrame\Core\Language::getModuleName($module);
} else {
	$base_uri=$module;
}
if (\osWFrame\Core\Session::getIsCrawler()===true) {
	$acceptable_parameters=$acceptable_spider_parameters;
} else {
	$acceptable_parameters=$acceptable_user_parameters;
}
$parameters=[];
foreach ($acceptable_parameters as $parameter) {
	if (isset($ar_parameters[$parameter])) {
		if ($parameter==\osWFrame\Core\Settings::getStringVar('session_name')) {
			if ($ar_parameters[$parameter]==\osWFrame\Core\Session::getId()) {
				$parameters[$parameter]=$ar_parameters[$parameter];
			} else {
				$parameters[$parameter]=\osWFrame\Core\Session::getId();
			}
		} else {
			$parameters[$parameter]=$ar_parameters[$parameter];
		}
	}
}
if (isset($parameters['action'])) {
	$base_uri.='/'.$parameters['action'];
	unset($parameters['action']);
} elseif (\osWFrame\Core\Settings::getAction()!='') {
	$base_uri.='/'.\osWFrame\Core\Settings::getAction();
}
$base_uri.='?';
foreach ($parameters as $key=>$value) {
	$base_uri.=$key.'='.$value.'&';
}
$base_uri=substr($base_uri, 0, -1);
if (isset($extend[1])) {
	$base_uri.='#'.$extend[1];
}

?>