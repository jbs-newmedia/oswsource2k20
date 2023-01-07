<?php

/**
 * This file is part of the VIS2 package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2
 * @link https://oswframe.com
 * @license MIT License
 */

/*
 * init
 */
$__datatable_table='ddm4_log';
$__datatable_create=false;
$__datatable_do=false;

/*
 * check version of table
 */
$QreadData=new \osWFrame\Core\Database();
$QreadData->prepare('SHOW TABLE STATUS LIKE :table:');
$QreadData->bindString(':table:', $this->getJSONStringValue('database_prefix').$__datatable_table);
$QreadData->execute();
if ($QreadData->rowCount()==1) {
	$QreadData_result=$QreadData->fetch();
	$avb_tbl=$QreadData_result['Comment'];
} else {
	$avb_tbl='0.0';
}
$avb_tbl=explode('.', $avb_tbl);
if (count($avb_tbl)==1) {
	$av_tbl=intval($avb_tbl[0]);
	$ab_tbl=0;
} elseif (count($avb_tbl)==2) {
	$av_tbl=intval($avb_tbl[0]);
	$ab_tbl=intval($avb_tbl[1]);
} else {
	$av_tbl=0;
	$ab_tbl=0;
}

/*
 * create table
 */
if (($av_tbl==0)&&($ab_tbl==0)) {
	$__datatable_create=true;
	$av_tbl=1;
	$ab_tbl=0;

	$QwriteData=new \osWFrame\Core\Database();
	$QwriteData->prepare('
CREATE TABLE :table: (
	log_id int(11) unsigned NOT NULL AUTO_INCREMENT,
	log_group varchar(64) NOT NULL DEFAULT \'\',
	name_index varchar(128) NOT NULL DEFAULT \'\',
	value_index varchar(128) NOT NULL DEFAULT \'\',
	log_key varchar(64) NOT NULL DEFAULT \'\',
	log_module varchar(128) NOT NULL DEFAULT \'\',
	log_value_new text NOT NULL DEFAULT \'\',
	log_value_old text NOT NULL DEFAULT \'\',
	log_value_user_id_new int(11) unsigned NOT NULL DEFAULT 0,
	log_value_user_id_old int(11) unsigned NOT NULL DEFAULT 0,
	log_value_time_new int(11) unsigned NOT NULL DEFAULT 0,
	log_value_time_old int(11) unsigned NOT NULL DEFAULT 0,
	PRIMARY KEY (log_id),
	KEY log_group (log_group),
	KEY name_index (name_index),
	KEY value_index (value_index),
	KEY log_key (log_key),
	KEY log_value_user_id_new (log_value_user_id_new),
	KEY log_value_user_id_old (log_value_user_id_old),
	KEY log_value_time_new (log_value_time_new),
	KEY log_value_time_old (log_value_time_old)
) ENGINE=:engine: DEFAULT CHARSET=:charset: COMMENT=:version:;
');
	$QwriteData->bindRaw(':table:', $this->getJSONStringValue('database_prefix').$__datatable_table);
	$QwriteData->bindString(':engine:', $this->getJSONStringValue('database_engine'));
	$QwriteData->bindString(':charset:', $this->getJSONStringValue('database_character'));
	$QwriteData->bindString(':version:', $av_tbl.'.'.$ab_tbl);
	$QwriteData->execute();
	if ($QwriteData->hasError()===true) {
		$tables_error[]='table:'.$__datatable_table.', patch:'.$av_tbl.'.'.$ab_tbl;
		$db_error[]=$QwriteData->getErrorMessage();
		$av_tbl=0;
		$ab_tbl=0;
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
if ($__datatable_do===true) {
	$QwriteData=new \osWFrame\Core\Database();
	$QwriteData->prepare('ALTER TABLE :table: COMMENT = :version:;');
	$QwriteData->bindRaw(':table:', $this->getJSONStringValue('database_prefix').$__datatable_table);
	$QwriteData->bindString(':version:', $av_tbl.'.'.$ab_tbl);
	$QwriteData->execute();
	if ($QwriteData->hasError()===true) {
		$tables_error[]='table:'.$__datatable_table.', patch:'.$av_tbl.'.'.$ab_tbl;
		$db_error[]=$QwriteData->getErrorMessage();
	}
}

?>