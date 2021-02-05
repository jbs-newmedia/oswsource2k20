<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package oswFrame - Updater
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 *
 */

/*
 * TOOL - init
 */

define('abs_path', str_replace('\\', '/', str_replace(basename(dirname(__FILE__)), '', dirname(__FILE__))));

define('serverlist', 'oswframe2k20');
define('package', 'tools.projectmanager');
define('release', 'stable');

include abs_path.'resources/includes/header.inc.php';

osW_Tool::getInstance()->initTool(package, release);

osW_Tool_Server::getInstance()->readServerList(serverlist);
osW_Tool_Server::getInstance()->updatePackageList(serverlist);
if (osW_Tool::getInstance()->checkUpdate(serverlist)==true) {
	if (osW_Tool::getInstance()->getAction()=='update') {
		osW_Tool::getInstance()->Update(serverlist);
	} else {
		if (osW_Tool_Session::getInstance()->getTool(package, 'checkupdate')!==true) {
			osW_Tool_Session::getInstance()->setTool(package, 'checkupdate', true);
$script='
$(function() {
	confirm(\'Update <strong>'.osW_Tool::getInstance()->getToolValue('name').'</strong> to version <strong>'.osW_Tool::getInstance()->getUpdateVersion(serverlist).'</strong>\', \'index.php?action=update&session='.osW_Tool_Session::getInstance()->getId().'\');
});';
		}
	}
}

/*
 * TOOL - configure
 */
$navigation=array();
$navigation['start']=array(
	'links'=>array(),
	'action'=>'start',
	'title'=>'Start',
	'icon'=>'home',
);
$navigation['settings']=array(
	'links'=>array(),
	'title'=>'More',
	'icon'=>'gear',
);
$navigation['settings']['links'][]=array(
	'links'=>array(),
	'action'=>'changelog',
	'title'=>'Changelog',
	'icon'=>'list',
);
$navigation['settings']['links'][]=array(
	'links'=>array(),
	'action'=>'about',
	'title'=>'About',
	'icon'=>'info',
);

$actions=['start', 'changelog', 'about'];
osW_Tool::getInstance()->setAction(osW_Tool::getInstance()->validateActions($actions));
$navigation=osW_Tool::getInstance()->prepaireNavigation($navigation);


/*
 * TOOL - main
 */
$global_css[]='../resources/css/dataTables.bootstrap.min.css';
$global_js[]='../resources/js/jquery.dataTables.min.js';
$global_js[]='../resources/js/dataTables.bootstrap.min.js';
$global_js[]='../resources/js/oswtools.projectmanager.js';

echo osW_Tool_Template::getInstance()->outputB3Header($navigation, $global_css, $global_js, $script);

include abs_path.package.'.'.release.'/'.osW_Tool::getInstance()->getAction().'.inc.php';

echo osW_Tool_Template::getInstance()->outputB3Footer();

?>