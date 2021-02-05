<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package oswFrame - Tools
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 *
 */

date_default_timezone_set('Europe/Berlin');

define('root_path', str_replace('\\', '/', realpath(abs_path.'../').'/'));

include(abs_path.'resources/includes/debuglib.inc.php');
include(abs_path.'resources/includes/functions.inc.php');

$global_js=array();
$global_css=array();
$script='';

$global_css[]='../resources/css/bootstrap.min.css';
$global_css[]='../resources/css/font-awesome.min.css';
$global_css[]='../resources/css/oswtools.css';
$global_js[]='../resources/js/jquery.min.js';
$global_js[]='../resources/js/bootstrap.min.js';
$global_js[]='../resources/js/bootbox.min.js';
$global_js[]='../resources/js/oswtools.js';

osW_Tool_Session::getInstance()->set('time', time());

?>