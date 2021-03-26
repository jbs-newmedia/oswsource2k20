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

function print_a($var) {
	ob_start();
	var_dump($var);
	$content=ob_get_contents();
	ob_end_clean();
	echo '<pre style="border:2px solid red; padding:3px;"><strong>print_a</strong><br/>'.$content.'</pre>';
}

?>