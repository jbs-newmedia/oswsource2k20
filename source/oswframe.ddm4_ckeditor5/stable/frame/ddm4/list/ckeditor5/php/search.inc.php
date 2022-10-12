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

$ddm_search_case_array[]=$this->getGroupOption('alias', 'database').'.'.$key.' LIKE '.self::getConnection()->escapeString('%'.$search['value'].'%');

?>