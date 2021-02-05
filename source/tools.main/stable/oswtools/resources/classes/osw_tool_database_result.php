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

class osW_Tool_Database_Result extends osW_Tool_Object {

	/* PROPERTIES */
	public $db_connection='';

	public $result=array();

	public $query_handler=false;

	public $limitrows=array();

	public $query='';

	public $error='';

	/* METHODS CORE */
	public function __construct($db_connection) {
		$this->db_connection=$db_connection;
	}

	/* METHODS */
	public function setQuery($query) {
		$this->query=$query;
	}

	public function getQuery() {
		return $this->query;
	}

	public function value($column) {
		return $this->valueMixed($column);
	}

	public function valueProtected($column) {
		return $this->valueMixed($column, 'protected');
	}

	public function valueInt($column) {
		return $this->valueMixed($column, 'int');
	}

	public function valueDecimal($column) {
		return $this->valueMixed($column, 'decimal');
	}

	private function valueMixed($column, $type='string') {
		if (!isset($this->result[$column])) {
			return '';
		}
		switch ($type) {
			case 'protected' :
				return osW_Tool::getInstance()->outputString($this->result[$column]);
				break;
			case 'int' :
				return intval($this->result[$column]);
				break;
			case 'decimal' :
				return floatval($this->result[$column]);
				break;
			case 'string' :
			default :
				return $this->result[$column];
		}
	}

	public function bindTable($place_holder, $value) {
		$this->bindValueMixed($place_holder, $this->db_connection->options['prefix'].$value, 'raw');
	}

	public function bindValue($place_holder, $value) {
		$this->bindValueMixed($place_holder, $value, 'string');
	}

	public function bindInt($place_holder, $value) {
		$this->bindValueMixed($place_holder, $value, 'int');
	}

	public function bindFloat($place_holder, $value) {
		$this->bindValueMixed($place_holder, $value, 'decimal');
	}

	public function bindDecimal($place_holder, $value) {
		$this->bindValueMixed($place_holder, $value, 'decimal');
	}

	public function bindRaw($place_holder, $value) {
		$this->bindValueMixed($place_holder, $value, 'raw');
	}

	public function setPrimaryKey($pkey) {
		$this->limitrows['primary_key']=$pkey;
	}

	public function bindLimit($max_rows=100, $page=0, $display_range=5, $page_holder='page') {
		if ($page==0) {
			$page=intval(h()->_catch($page_holder, 1, 'gp'));
		}
		if ($page<1) {
			$page=1;
		}
		$this->limitrows['current_page_number']=intval($page);
		$this->limitrows['number_of_pages']=1;
		$this->limitrows['number_of_rows']=0;
		$this->limitrows['number_of_rows_per_page']=$max_rows;
		$this->limitrows['number_of_rows_on_page']=0;
		$this->limitrows['display_range']=$display_range;
		if ((isset($this->limitrows['primary_key']))&&(strlen($this->limitrows['primary_key'])>0)) {
			$this->execute(preg_replace('/SELECT(.*)\ FROM/Uis', 'SELECT count('.$this->limitrows['primary_key'].') AS osWCounter_Temp FROM', $this->getQuery()));
		} else {
			$this->execute();
		}

		if ((isset($this->limitrows['primary_key']))&&(strlen($this->limitrows['primary_key'])>0)) {
			$this->next();
			$this->limitrows['number_of_rows']=$this->Value('osWCounter_Temp');
		} else {
			$this->limitrows['number_of_rows']=$this->numberOfRows();
		}
		$this->limitrows['number_of_pages'] = ceil($this->limitrows['number_of_rows']/$this->limitrows['number_of_rows_per_page']);
		if ($this->limitrows['current_page_number'] > $this->limitrows['number_of_pages']) {
			if ($this->limitrows['number_of_pages'] > 0) {
				$this->limitrows['current_page_number'] = $this->limitrows['number_of_pages'];
			}
		}

		$offset = ($this->limitrows['number_of_rows_per_page']*($this->limitrows['current_page_number']-1));
		if ($this->limitrows['current_page_number']==$this->limitrows['number_of_pages']) {
			$this->limitrows['number_of_rows_on_page']=$this->limitrows['number_of_rows']-$offset;
		} else {
			$this->limitrows['number_of_rows_on_page']=$this->limitrows['number_of_rows_per_page'];
		}
		$this->setQuery($this->getQuery().' LIMIT '.$offset.', '.$this->limitrows['number_of_rows_per_page']);
	}

	private function bindValueMixed($place_holder, $value, $type='string') {
		$value=trim($value);
		switch ($type) {
			case 'int' :
				$value=intval($value);
				break;
			case 'decimal' :
				$value=str_replace(',', '.', $value);
				$value=floatval($value);
				$value=''.str_replace(',', '.', $this->db_connection->_escape_string($value, $this->db_connection->link)).'';
			case 'raw' :
				break;
			case 'string' :
			default :
				$value='\''.$this->db_connection->_escape_string($value, $this->db_connection->link).'\'';
		}
		$this->bindReplace($place_holder, $value);
	}

	private function bindReplace($place_holder, $value) {
		$this->query=str_replace($place_holder, $value, $this->query);
	}

	public function next() {
		if (isset($this->query_handler)) {
			$this->result=$this->db_connection->_next($this->query_handler);
		} else {
			$this->result=array();
		}
		return $this->result;
	}

	public function numberOfRows() {
		if (isset($this->query_handler)) {
			$this->rows=$this->db_connection->_numberOfRows($this->query_handler);
		} else {
			$this->rows=0;
		}
		return $this->rows;
	}

	public function nextID() {
		if (isset($this->query_handler)) {
			$this->nextid=$this->db_connection->_insert_id($this->db_connection->link);
		} else {
			$this->nextid=0;
		}
		return $this->nextid;
	}

	public function affectedRows() {
		if (isset($this->query_handler)) {
			$this->affectedrows=$this->db_connection->_affected_rows($this->db_connection->link);
		} else {
			$this->affectedrows=0;
		}
		return $this->affectedrows;
	}

	public function isError() {
		if ($this->error===false) {
			return false;
		}
		return true;
	}

	public function getError() {
		return $this->error;
	}

	public function getResult() {
		return $this->result;
	}

	public function execute($query='') {
		if ($query=='') {
			$query=$this->query;
		}
		$this->query_handler=$this->db_connection->simpleQuery($query);
		if ($this->db_connection->getError()!==false) {
			$this->error=$this->db_connection->getError();
		}
		return $this->query_handler;
	}

	/**
	 *
	 * @return osW_Tool_Database_Result
	 */
	public static function getInstance($alias='default') {
		return parent::getInstance($alias);
	}
}

?>