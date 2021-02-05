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

class osW_Tool_Database_mysql extends osW_Tool_Database_Engine {

	/* METHODS CORE */
	public function __construct($alias, $options) {
		parent::__construct($alias, $options, __CLASS__, 2, 0);
	}

	public function __destruct() {
		parent::__destruct();
	}

	/* METHODS */
	public function _connect() {
		$link=mysqli_connect($this->options['server'], $this->options['username'], $this->options['password']);
		if ($this->_select_db($this->options['database'], $link)===false) {
			return false;
		}
		return $link;
	}

	public function _init() {
		// set UTF8 flags
		$setUTF8=$this->query('SET character_set_client="utf8"');
		$setUTF8->execute();
		$setUTF8=$this->query('SET character_set_connection="utf8"');
		$setUTF8->execute();
		$setUTF8=$this->query('SET character_set_results="utf8"');
		$setUTF8->execute();
		return true;
	}

	public function _pconnect() {
		$link=mysqli_connect('p:'.$this->options['server'], $this->options['username'], $this->options['password']);
		if ($this->_select_db($this->options['database'], $link)===false) {
			return false;
		}
		return $link;
	}

	public function _close($link) {
		return mysqli_close($link);
	}

	public function _select_db($database_name, $link) {
		return mysqli_select_db($link, $database_name);
	}

	public function _query($query, $link) {
		return mysqli_query($link, $query);
	}

	public function _error($link) {
		if ($link===false) {
			return mysqli_error();
		} else {
			return mysqli_error($link);
		}
	}

	public function _errno($link) {
		if ($link===false) {
			return mysqli_errno();
		} else {
			return mysqli_errno($link);
		}
	}

	public function _escape_string($str, $link) {
		return mysqli_real_escape_string($link, $str);
	}

	public function _numberOfRows($resource) {
		if ($resource!==false) {
			return mysqli_num_rows($resource);
		}
		return 0;
	}

	public function _next($resource) {
		if ($resource!==false) {
			return mysqli_fetch_assoc($resource);
		}
		return array();
	}

	public function _insert_id($link) {
		if ($link!==false) {
			return mysqli_insert_id($link);
		}
		return 0;
	}

	public function _affected_rows($link) {
		if ($link!==false) {
			return mysqli_affected_rows($link);
		}
		return 0;
	}

	public function _info($link) {
		return mysqli_get_server_info($link);
	}

	/**
	 *
	 * @return osW_Tool_Database_mysql
	 */
	public static function getInstance($alias='default') {
		return parent::getInstance($alias);
	}
}

?>