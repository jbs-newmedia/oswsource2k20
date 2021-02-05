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

class osW_Tool_Database_Engine extends osW_Tool_Object {

	/* PROPERTIES */
	public $alias='';

	public $link='';

	public $querytime=0;

	public $options=array();

	/* METHODS CORE */
	public function __construct($alias, $options, $class, $version, $build) {
		parent::__construct($class, $version, $build);
		$this->alias=$alias;
		$this->options=$options;
		$this->connect();
		$this->init();
	}

	public function __destruct() {
		$this->close();
		parent::__destruct();
	}

	/* METHODS */
	public function connect() {
		if ($this->options['pconnect']===true) {
			$this->link=$this->_pconnect();
		} else {
			$this->link=$this->_connect();
		}

		#unset($this->options['database']);
		unset($this->options['server']);
		unset($this->options['username']);
		unset($this->options['password']);

		if ($this->link===false) {
			$this->setError($this->error($this->link), $this->errno($this->link));
			$this->dieError();
			//return false;
		}

		$this->setConnected(true);
		//return true;
	}

	public function dieError() {
		h()->_die('Database-Error: '.$this->error($this->link).' (No:'.$this->errno($this->link).')');
	}

	public function init() {
		$this->_init();
		return true;
	}

	public function info() {
		return $this->_info($this->link);
	}

	public function close() {
		if ($this->isConnected()) {
			if ($this->_close($this->link)===true) {
				return true;
			}
			return false;
		} else {
			return true;
		}
	}

	public function setConnected($boolean) {
		if ($boolean===true) {
			$this->is_connected=true;
			return true;
		}
		$this->is_connected=false;
		return false;
	}

	public function isConnected() {
		if ($this->is_connected===true) {
			return true;
		}
		return false;
	}

	public function setError($error, $error_number='') {
		$this->error=$error;
		$this->error_number=$error_number;
	}

	public function isError() {
		if ($this->error===false) {
			return false;
		}
		return true;
	}

	public function getError() {
		if ($this->isError()) {
			$error='';
			if (!empty($this->error_number)) {
				$error.=$this->error_number.': ';
			}
			$error.=$this->error;
			return $error;
		} else {
			return false;
		}
	}

	private function freeQuery() {
		$this->query='';
		$this->query_handler=false;
		$this->rows=0;
		$this->affectedrows=0;
		$this->nextid=0;
		$this->resource='';
		$this->result=array();
	}

	public function query($query) {
		$osW_Database_Result=new osW_Tool_Database_Result($this);
		$osW_Database_Result->setQuery($query);
		return $osW_Database_Result;
	}

	public function simpleQuery($query) {
		if ($this->isConnected()) {
			$this->setError(false);
			$time_start=$this->getMicroTime();
			$resource=$this->_query($query, $this->link);
			$time_end=$this->getMicroTime();
			$this->querytime=number_format($time_end-$time_start, 5);
			if (!isset(osW_Tool_Database::$db_stats[osW_Tool_Database::$db_alias])) {
				osW_Tool_Database::$db_stats[$this->alias]=array();
			}
			if (!isset(osW_Tool_Database::$db_stats[$this->alias]['number_of_queries'])) {
				osW_Tool_Database::$db_stats[$this->alias]['number_of_queries']=0;
			}
			if (!isset(osW_Tool_Database::$db_stats[$this->alias]['time_of_queries'])) {
				osW_Tool_Database::$db_stats[$this->alias]['time_of_queries']=0;
			}
			osW_Tool_Database::$db_stats[$this->alias]['number_of_queries']++;
			osW_Tool_Database::$db_stats[$this->alias]['time_of_queries']+=$this->querytime;
			if ($resource!==false) {
				return $resource;
			} else {
				$this->setError($this->error($this->link), $this->errno($this->link));
				return false;
			}
		} else {
			return false;
		}
	}

	public function error() {
		return $this->_error($this->link);
	}

	public function errno() {
		return $this->_errno($this->link);
	}

	public function escape_string($str) {
		return $this->_escape_string($str, $this->link);
	}

	public function getMicroTime() {
		return microtime(true);
	}

	public function numberOfQueries() {
		return $this->number_of_queries;
	}

	public function timeOfQueries() {
		return $this->time_of_queries;
	}
}

?>