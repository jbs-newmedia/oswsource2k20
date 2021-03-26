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

namespace osWFrame\Core;

class Database {

	use BaseStaticTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=1;

	/**
	 * Minor-Version der Klasse.
	 */
	private const CLASS_MINOR_VERSION=0;

	/**
	 * Release-Version der Klasse.
	 */
	private const CLASS_RELEASE_VERSION=0;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	/**
	 * Speichert das Datenbank-Objekt als PDO
	 *
	 * @var ?object
	 */
	public ?object $PDO=null;

	/**
	 * Speichert das Result-Objekt als PDOStatement.
	 *
	 * @var ?object
	 */
	public ?object $PDOStatement=null;

	/**
	 *
	 * @var array
	 */
	public array $result=[];

	/**
	 *
	 * @var string
	 */
	public string $query='';

	/**
	 *
	 * @var array
	 */
	public array $limitrows=[];

	/**
	 * Database constructor.
	 *
	 * @param string $alias
	 */
	public function __construct($alias='default') {
		$this->PDO=DB::getConnection($alias);
	}

	/**
	 *
	 * @param string $query
	 * @return array
	 */
	public function prepare($query) {
		$this->setQuery($query);
	}

	/**
	 *
	 * @param string $name
	 * @param string $value
	 * @return bool
	 */
	public function bindTable(string $name, string $value):bool {
		$this->setQuery(str_replace($name, Settings::getStringVar('database_prefix').$value, $this->getQuery()));

		return true;
	}

	/**
	 *
	 * @param string $name
	 * @param bool $value
	 * @return bool
	 */
	public function bindBool(string $name, bool $value):bool {
		$this->setQuery(str_replace($name, intval($value), $this->getQuery()));

		return true;
	}

	/**
	 *
	 * @param string $name
	 * @param string $value
	 * @return bool
	 */
	public function bindString(string $name, string $value):bool {
		$this->setQuery(str_replace($name, $this->escapeString($value), $this->getQuery()));

		return true;
	}

	/**
	 *
	 * @param string $name
	 * @param string $value
	 * @return bool
	 */
	public function bindCrypt(string $name, string $value):bool {
		$this->setQuery(str_replace($name, $this->escapeString(StringFunctions::encryptString($value, 'sha512', 6)), $this->getQuery()));

		return true;
	}

	/**
	 *
	 * @param string $name
	 * @param int $value
	 * @return bool
	 */
	public function bindInt(string $name, int $value):bool {
		$this->setQuery(str_replace($name, $value, $this->getQuery()));

		return true;
	}

	/**
	 *
	 * @param string $name
	 * @param float $value
	 * @return bool
	 */
	public function bindFloat(string $name, float $value):bool {
		$value=str_replace(',', '.', strval($value));
		$this->setQuery(str_replace($name, $value, $this->getQuery()));

		return true;
	}

	/**
	 *
	 * @param string $name
	 * @param string $value
	 * @return bool
	 */
	public function bindRaw(string $name, string $value):bool {
		$this->setQuery(str_replace($name, $value, $this->getQuery()));

		return true;
	}

	/**
	 *
	 * @param string $primay_key
	 * @param int $max_rows
	 * @param int $page
	 * @param string $page_holder
	 * @return bool
	 */
	public function bindLimit(string $primay_key, int $number_of_rows_per_page=100, int $current_page_number=0, string $page_holder='page'):bool {
		if ($current_page_number==0) {
			$current_page_number=intval(h()->_catch($page_holder, 1, 'gp'));
		}
		if ($current_page_number<1) {
			$current_page_number=1;
		}
		$this->limitrows=[];
		$this->limitrows['current_page_number']=$current_page_number;
		$this->limitrows['number_of_pages']=1;
		$this->limitrows['number_of_rows']=0;
		$this->limitrows['number_of_rows_per_page']=$number_of_rows_per_page;
		$this->limitrows['number_of_rows_on_page']=0;
		$query=$this->getQuery();
		if (($pos=strpos($query, 'ORDER'))>0) {
			$query=substr($query, 0, $pos);
		}
		$query=trim(preg_replace('/SELECT(.*)\ FROM/Uis', 'SELECT COUNT('.$primay_key.') AS osWCounter_Temp FROM', $query));
		$this->execute($query);
		$this->fetch();
		$this->limitrows['number_of_rows']=$this->getInt('osWCounter_Temp');
		$this->limitrows['number_of_pages']=ceil($this->limitrows['number_of_rows']/$this->limitrows['number_of_rows_per_page']);
		if ($this->limitrows['current_page_number']>$this->limitrows['number_of_pages']) {
			if ($this->limitrows['number_of_pages']>0) {
				$this->limitrows['current_page_number']=$this->limitrows['number_of_pages'];
			}
		}
		$offset=($this->limitrows['number_of_rows_per_page']*($this->limitrows['current_page_number']-1));
		if ($this->limitrows['current_page_number']==$this->limitrows['number_of_pages']) {
			$this->limitrows['number_of_rows_on_page']=$this->limitrows['number_of_rows']-$offset;
		} else {
			$this->limitrows['number_of_rows_on_page']=$this->limitrows['number_of_rows_per_page'];
		}
		$this->setQuery($this->getQuery().' LIMIT '.$offset.', '.$this->limitrows['number_of_rows_per_page']);

		return true;
	}

	/**
	 *
	 * @param string $query
	 * @return bool
	 */
	public function setQuery(string $query):bool {
		$this->query=$query;

		return true;
	}

	/**
	 *
	 * @return string
	 */
	public function getQuery():string {
		return $this->query;
	}

	/**
	 *
	 * @param string $string
	 * @return string
	 */
	public function escapeString(string $string):string {
		return $this->PDO->quote($string);
	}

	/**
	 *
	 * @param string $query
	 * @return bool|null
	 */
	public function execute(string $query=''):?bool {
		if ($query=='') {
			$query=$this->getQuery();
		}
		$this->PDOStatement=$this->PDO->prepare($query);

		$result=$this->PDOStatement->execute();

		if ($result===false) {
			$this->logError($this->PDOStatement->errorInfo(), $query);

			return null;
		}

		return $result;
	}

	/**
	 *
	 * @param string $query
	 * @return int
	 */
	public function exec(string $query=''):int {
		if ($this->execute($query)===true) {
			return $this->rowCount();
		} else {
			return 0;
		}
	}

	/**
	 * @param string $query
	 * @return \PDOStatement|null
	 */
	public function query(string $query=''):?\PDOStatement {
		if ($query=='') {
			$query=$this->getQuery();
		}
		$result=$this->PDO->query($query, \PDO::FETCH_ASSOC);

		if ($result===false) {
			$this->logError($this->PDO->errorInfo(), $query);

			return null;
		}

		return $result;
	}

	/**
	 *
	 * @return int
	 */
	public function rowCount():int {
		return $this->PDOStatement->rowCount();
	}

	/**
	 *
	 * @return array|null
	 */
	public function fetch():?array {
		$this->result=$this->PDOStatement->fetch(\PDO::FETCH_ASSOC);
		if ($this->result===false) {
			// TODO: Fehler
			return null;
		}

		return $this->result;
	}

	/**
	 *
	 * @return DatabaseResult|null
	 */
	public function next():?DatabaseResult {
		$this->result=$this->PDOStatement->fetch(\PDO::FETCH_ASSOC);
		if ($this->result===false) {
			// TODO: Fehler
			return null;
		}

		return new DatabaseResult($this->result);
	}

	/**
	 *
	 * @param string $name
	 * @return int
	 */
	public function lastInsertId(string $name=null):int {
		return $this->PDO->lastInsertId($name);
	}

	/**
	 *
	 * @param string $name
	 * @return array|null
	 */
	public function getResult(string $name):?array {
		if ($this->result) {
			return $this->result;
		}

		return null;
	}

	/**
	 *
	 * @param string $name
	 * @return bool|null
	 */
	public function getBool(string $name):?bool {
		if (isset($this->result[$name])) {
			return $this->result[$name];
		}

		return null;
	}

	/**
	 *
	 * @param string $name
	 * @return string|null
	 */
	public function getValue(string $name):?string {
		return $this->getString($name);
	}

	/**
	 *
	 * @param string $name
	 * @return string|null
	 */
	public function getString(string $name):?string {
		if (isset($this->result[$name])) {
			return $this->result[$name];
		}

		return null;
	}

	/**
	 *
	 * @param string $name
	 * @return int|null
	 */
	public function getInt(string $name):?int {
		if (isset($this->result[$name])) {
			return $this->result[$name];
		}

		return null;
	}

	/**
	 *
	 * @param string $name
	 * @return float|null
	 */
	public function getFloat(string $name):?float {
		if (isset($this->result[$name])) {
			return $this->result[$name];
		}

		return null;
	}

	/**
	 * @param array $result
	 * @return bool
	 */
	public function logError(array $result, string $query=''):bool {
		MessageStack::addMessage(self::getNameAsString(), 'error', ['time'=>time(), 'line'=>__LINE__, 'function'=>__FUNCTION__, 'error'=>$result[2], 'query'=>$query, 'errno'=>$result[1]]);

		return true;
	}

}

?>