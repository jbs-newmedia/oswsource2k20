<?php

/**
 *
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package osWFrame
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 *
 */

class osW_Tool_Database extends osW_Tool_Object {

	/* VALUES */
	static $db_pool=array();

	static $db_alias='';

	static $db_stats=array();

	/* METHODS CORE */
	public function __construct() {
		// not used by static functions only
	}

	public function __destruct() {
		// not used by static functions only
	}

	/* METHODS */
	static function connect($alias, $options) {
		$db_stats[$alias]['number_of_queries']=0;
		$db_stats[$alias]['time_of_queries']=0;
		switch ($options['type']) {
			case 'odbc' :
				return new osW_Tool_Database_odbc($alias, $options);
				break;
			case 'pqsql' :
				return new osW_Tool_Database_pgsql($alias, $options);
				break;
			case 'mysql' :
				return new osW_Tool_Database_mysql($alias, $options);
				break;
			case 'sqllite' :
				return new osW_Tool_Database_sqllite($alias, $options);
				break;
		}
		return false;
	}

	static function addDatabase($db_alias, $db_options=array()) {
		if (self::$db_alias=='') {
			self::$db_alias=$db_alias;
		}
		self::$db_pool[$db_alias]=$db_options;
	}

	/* INSTANCE */
	static $db_objects;

	/**
	 *
	 * @return osW_Tool_Database
	 */

	static function getInstance($db_alias='') {
		if ($db_alias=='') {
			$db_alias=self::$db_alias;
		}
		if (!isset(self::$db_objects['obj_'.$db_alias])||!is_object(self::$db_objects['obj_'.$db_alias])) {
			self::$db_objects['obj_'.$db_alias]=osW_Tool_Database::connect($db_alias, self::$db_pool[$db_alias]);
		}
		return self::$db_objects['obj_'.$db_alias];
	}
}

?>