<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package oswFrame - Tools
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 *
 */

/*
 * TOOL - Changelog
 */

define('abs_path', str_replace('\\', '/', str_replace(basename(dirname(__FILE__)), '', dirname(__FILE__))));

define('serverlist', 'oswframe2k20');
define('package', 'tools.adminer');
define('release', 'stable');

include abs_path.'resources/includes/header.inc.php';

function adminer_object() {
	// required to run any plugin
	include_once abs_path.'resources/php/adminer/plugins/plugin.php';

	// autoloader
	foreach (glob(abs_path.'resources/php/adminer/plugins/*.php') as $filename) {
		include_once $filename;
	}

	$_designs=glob(abs_path.'resources/php/adminer/designs/*');
	$designs=array();
	foreach ($_designs as $design) {
		$designs[basename($design)]=basename($design);
	}

	$plugins = array(
		// specify enabled plugins here
		new AdmineroswTools,
		new AdminerFrames,
		new FillLoginForm('server', osW_Tool::getInstance()->getFrameConfig('database_server'), osW_Tool::getInstance()->getFrameConfig('database_username'), osW_Tool::getInstance()->getFrameConfig('database_password'), osW_Tool::getInstance()->getFrameConfig('database_db')),
		new AdminerDesigns($designs),
		new AdminerTableHeaderScroll(),
	);

	#	osW_Settings::getInstance()->database_type='mysql';

	return new AdminerPlugin($plugins);
}

include abs_path.'resources/php/adminer/adminer-4.7.8.php';
die();

?>