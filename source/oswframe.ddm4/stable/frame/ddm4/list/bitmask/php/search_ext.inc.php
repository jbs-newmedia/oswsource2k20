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

if ((isset($data[$element]))&&(!in_array($data[$element], ['']))) {
	$ddm_search_case_array[]=$this->getGroupOption('alias', 'database').'.'.$options['name'].' LIKE '.self::getConnection()->escapeString($data[$element]).'';
}

?>