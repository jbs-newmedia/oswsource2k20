<?php declare(strict_types=0);

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license MIT License
 *
 * @var \osWFrame\Tools\Tool\Configure $this
 *
 */

/*
 * init
 */
$__datatable_table = 'api_database_column';
$__datatable_create = false;
$__datatable_do = false;

/*
 * check version of table
 */
$QreadData = new \osWFrame\Core\Database();
$QreadData->prepare('SHOW TABLE STATUS LIKE :table:');
$QreadData->bindString(':table:', $this->getJSONStringValue('database_prefix') . $__datatable_table);
$QreadData->execute();
if ($QreadData->rowCount() === 1) {
    $QreadData_result = $QreadData->fetch();
    $avb_tbl = $QreadData_result['Comment'];
} else {
    $avb_tbl = '0.0';
}
$avb_tbl = explode('.', $avb_tbl);
if (count($avb_tbl) === 1) {
    $av_tbl = (int) ($avb_tbl[0]);
    $ab_tbl = 0;
} elseif (count($avb_tbl) === 2) {
    $av_tbl = (int) ($avb_tbl[0]);
    $ab_tbl = (int) ($avb_tbl[1]);
} else {
    $av_tbl = 0;
    $ab_tbl = 0;
}

/*
 * create table
 */
if (($av_tbl === 0) && ($ab_tbl === 0)) {
    $__datatable_create = true;
    $av_tbl = 1;
    $ab_tbl = 0;

    $QwriteData = new \osWFrame\Core\Database();
    $QwriteData->prepare('
CREATE TABLE :table: (
	table_name varchar(64) NOT NULL DEFAULT \'\',
	column_name varchar(64) NOT NULL DEFAULT \'\',
	column_position int(11) unsigned NOT NULL DEFAULT 0,
	column_type varchar(32) NOT NULL DEFAULT \'\',
	column_length int(11) unsigned NOT NULL DEFAULT 0,
	column_collation varchar(32) NOT NULL DEFAULT \'\',
	column_options varchar(64) NOT NULL DEFAULT \'\',
	column_convert varchar(16) NOT NULL DEFAULT \'\',
	column_setnull tinyint(1) unsigned NOT NULL DEFAULT 0,
	column_autoincrement tinyint(1) unsigned NOT NULL DEFAULT 0,
	column_create_time int(11) unsigned NOT NULL DEFAULT 0,
	column_create_user_id int(11) unsigned NOT NULL DEFAULT 0,
	column_update_time int(11) unsigned NOT NULL DEFAULT 0,
	column_update_user_id int(11) unsigned NOT NULL DEFAULT 0,
	KEY table_name (table_name),
	KEY column_name (column_name),
	KEY column_create_time (column_create_time),
	KEY column_create_user_id (column_create_user_id),
	KEY column_update_time (column_update_time),
	KEY column_update_user_id (column_update_user_id)
) ENGINE=:engine: DEFAULT CHARSET=:charset: COMMENT=:version:;
');
    $QwriteData->bindRaw(':table:', $this->getJSONStringValue('database_prefix') . $__datatable_table);
    $QwriteData->bindString(':engine:', $this->getJSONStringValue('database_engine'));
    $QwriteData->bindString(':charset:', $this->getJSONStringValue('database_character'));
    $QwriteData->bindString(':version:', $av_tbl . '.' . $ab_tbl);
    $QwriteData->execute();
    if ($QwriteData->hasError() === true) {
        $tables_error[] = 'table:' . $__datatable_table . ', patch:' . $av_tbl . '.' . $ab_tbl;
        $db_error[] = $QwriteData->getErrorMessage();
        $av_tbl = 0;
        $ab_tbl = 0;
    }
}

/*
 * update table DBV-1.1
 */
/*
if (($av_tbl==1)&&($ab_tbl==1)) {
    $__datatable_do=true;
    $av_tbl=1;
    $ab_tbl=1;

    ... query ...
    if ($QupdateData->hasError()===true) {
        $tables_error[]='table:'.$__datatable_table.', patch:'.$av_tbl.'.'.$ab_tbl;
        $db_error[]=$QupdateData->getErrorMessage();
        $av_tbl=1;
        $ab_tbl=0;
    }
}
*/

/*
 * update version
 */
if ($__datatable_do === true) {
    $QwriteData = new \osWFrame\Core\Database();
    $QwriteData->prepare('ALTER TABLE :table: COMMENT = :version:;');
    $QwriteData->bindString(':table:', $this->getJSONStringValue('database_prefix') . $__datatable_table);
    $QwriteData->bindString(':version:', $av_tbl . '.' . $ab_tbl);
    $QwriteData->execute();
    if ($QwriteData->hasError() === true) {
        $tables_error[] = 'table:' . $__datatable_table . ', patch:' . $av_tbl . '.' . $ab_tbl;
        $db_error[] = $QwriteData->getErrorMessage();
    }
}
