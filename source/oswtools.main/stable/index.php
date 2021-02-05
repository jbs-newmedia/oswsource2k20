<?php

/**
 *
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package osWTools - Main
 * @link http://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 *
 */

#$__osW_Object__$#
#$__osW_Server__$#
#$__osW_Tool__$#
#$__osW_Zip__$#
date_default_timezone_set('Europe/Berlin');

define('abs_path', dirname(__FILE__).'/');
define('root_path', abs_path);
define('serverlist', 'oswframe2k20');

$json='#$__SERVERLIST__$#';

osW_Tool_Server::getInstance()->setServerList($json, serverlist);
osW_Tool_Server::getInstance()->connectServer(serverlist);

$index_installer=sha1_file(__FILE__);
osW_Tool::getInstance()->installPackageForce('tools.main', 'stable', serverlist, abs_path.'oswtools_installer.zip');
osW_Tool::getInstance()->installPackageForce('tools.manager', 'stable', serverlist, abs_path.'oswtools_installer.zip');
$index_new=sha1_file(__FILE__);
if ($index_installer==$index_new) {
	osW_Tool::getInstance()->delFile(__FILE__);
}

header('Location: oswtools/');

?>