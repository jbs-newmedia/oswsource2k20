<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package oswFrame - Tools
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 *
 */

spl_autoload_register(function($classname) {
	require abs_path.'resources/classes/'.strtolower($classname).'.php';
});

function outputString($str) {
	return $str;
}

?>