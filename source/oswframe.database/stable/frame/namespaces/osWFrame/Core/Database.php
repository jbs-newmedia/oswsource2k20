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
	private const CLASS_MINOR_VERSION=3;

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
	protected ?object $PDO=null;

	/**
	 * Speichert das Result-Objekt als PDOStatement.
	 *
	 * @var ?object
	 */
	protected ?object $PDOStatement=null;

	/**
	 * @var int|null
	 */
	protected ?int $result_key=null;

	/**
	 * @var int
	 */
	protected int $result_count=0;

	/**
	 *
	 * @var array
	 */
	protected array $result=[];

	/**
	 *
	 * @var array
	 */
	protected array $result_all=[];

	/**
	 *
	 * @var string
	 */
	protected string $query='';

	/**
	 * @var int
	 */
	protected int $query_count=0;

	/**
	 * @var float
	 */
	protected float $query_runtime=0;

	/**
	 *
	 * @var array
	 */
	protected array $limitrows=[];

	/**
	 * @var bool
	 */
	protected bool $error=false;

	/**
	 * @var string
	 */
	protected string $error_message='';

	/**
	 * @var array
	 */
	protected static array $stats=[];

	/**
	 * Database constructor.
	 *
	 * @param string $alias
	 */
	public function __construct(string $alias='default') {
		$this->PDO=DB::getConnection($alias);
	}

	/**
	 * @param string $query
	 * @return $this
	 */
	public function prepare(string $query):self {
		$this->setQuery($query);

		return $this;
	}

	/**
	 * @param string $name
	 * @param string $value
	 * @return $this
	 */
	public function bindTable(string $name, string $value):self {
		$this->setQuery(str_replace($name, Settings::getStringVar('database_prefix').$value, $this->getQuery()));

		return $this;
	}

	/**
	 * @param string $name
	 * @param bool $value
	 * @return $this
	 */
	public function bindBool(string $name, bool $value):self {
		$this->setQuery(str_replace($name, intval($value), $this->getQuery()));

		return $this;
	}

	/**
	 * @param string $name
	 * @param string $value
	 * @return $this
	 */
	public function bindString(string $name, string $value):self {
		$this->setQuery(str_replace($name, $this->escapeString($value), $this->getQuery()));

		return $this;
	}

	/**
	 * @param string $name
	 * @param string $value
	 * @return $this
	 */
	public function bindCrypt(string $name, string $value):self {
		$this->setQuery(str_replace($name, $this->escapeString(StringFunctions::encryptString($value, 'sha512', 6)), $this->getQuery()));

		return $this;
	}

	/**
	 * @param string $name
	 * @param int $value
	 * @return $this
	 */
	public function bindInt(string $name, int $value):self {
		$this->setQuery(str_replace($name, $value, $this->getQuery()));

		return $this;
	}

	/**
	 * @param string $name
	 * @param float $value
	 * @return $this
	 */
	public function bindFloat(string $name, float $value):self {
		$value=str_replace(',', '.', strval($value));
		$this->setQuery(str_replace($name, $value, $this->getQuery()));

		return $this;
	}

	/**
	 * @param string $name
	 * @param string $value
	 * @return $this
	 */
	public function bindRaw(string $name, string $value):self {
		$this->setQuery(str_replace($name, $value, $this->getQuery()));

		return $this;
	}

	/**
	 * @param string $primay_key
	 * @param int $number_of_rows_per_page
	 * @param int $current_page_number
	 * @param string $page_holder
	 * @return $this
	 */
	public function bindLimit(string $primay_key, int $number_of_rows_per_page=100, int $current_page_number=0, string $page_holder='page'):self {
		if ($current_page_number==0) {
			$current_page_number=intval(Settings::catchIntValue($page_holder, 1, 'gp'));
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

		Debug::startTimer(self::getNameAsString().'_query');
		$this->execute($query);
		Debug::stopTimer(self::getNameAsString().'_query');
		$this->checkSlowQuery(Debug::calcTimer(self::getNameAsString().'_query'), $query);

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

		return $this;
	}

	/**
	 * @return array
	 */
	public function getLimitRows():array {
		return $this->limitrows;
	}

	/**
	 * @param string $query
	 * @return $this
	 */
	public function setQuery(string $query):self {
		$this->query=$query;

		return $this;
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
	 * @return $this
	 */
	public function initResult():self {
		$this->result_key=null;
		$this->result_count=0;
		$this->result=[];
		$this->result_all=[];
		$this->error=false;
		$this->error_message='';

		return $this;
	}

	public function execute(string $query='', int $expire=0):bool {
		if ($query=='') {
			$query=$this->getQuery();
		}
		$this->initResult();
		if (($expire==0)||(null===($result=Cache::readCacheAsArray(self::getNameAsString(), 'execute-'.md5($query), $expire)))) {
			try {
				$this->PDOStatement=$this->PDO->prepare($query);
				Debug::startTimer(self::getNameAsString().'_query');
				$result_state=$this->PDOStatement->execute();
				Debug::stopTimer(self::getNameAsString().'_query');
				$this->checkSlowQuery(Debug::calcTimer(self::getNameAsString().'_query'), $query);
				$this->result_all=$this->PDOStatement->fetchAll(\PDO::FETCH_ASSOC);
				$this->result_count=$this->PDOStatement->rowCount();
				Cache::writeProtectedCacheArray(self::getNameAsString(), 'query-'.md5($query), $this->result_all);
				if (!isset(self::$stats['query_count'])) {
					self::$stats['query_count']=0;
				}
				self::$stats['query_count']++;
				$this->query_count=self::$stats['query_count'];
				$this->query_runtime=Debug::calcTimer(self::getNameAsString().'_query', 6);
			} catch (\PDOException $e) {
				$this->error=true;
				$this->error_message=$e->getMessage();
				$this->logPDOError('query', $e, $query);

				return false;
			}
		} else {
			$this->result_all=$result;
			$this->result_count=count($this->result_all);
		}

		return $result_state;
	}

	/**
	 * @param string $query
	 * @param int $expire
	 * @return int
	 */
	public function exec(string $query='', int $expire=0):int {
		if ($this->execute($query, $expire)===true) {
			return $this->rowCount();
		} else {
			return 0;
		}
	}

	/**
	 * @return $this
	 */
	public function dump():self {
		print_a(['query_number'=>$this->query_count, 'query_runtime'=>$this->query_runtime, 'query'=>$this->query, 'result_key'=>$this->result_key, 'result_count'=>$this->result_count, 'error'=>$this->error, 'error_message'=>$this->error_message]);

		return $this;
	}

	/**
	 * @return $this
	 */
	public function dumpResult():self {
		print_a(['result'=>$this->result]);

		return $this;
	}

	/**
	 * @return $this
	 */
	public function dumpResultAll():self {
		print_a(['result_all'=>$this->result_all]);

		return $this;
	}

	/**
	 * @param string $query
	 * @param int $expire
	 * @return array
	 */
	public function query(string $query='', int $expire=0):array {
		if ($query=='') {
			$query=$this->getQuery();
		}
		$this->initResult();
		if (($expire==0)||(null===($result=Cache::readCacheAsArray(self::getNameAsString(), 'query-'.md5($query), $expire)))) {
			try {
				Debug::startTimer(self::getNameAsString().'_query');
				$result_state=$this->PDO->query($query, \PDO::FETCH_ASSOC);
				Debug::stopTimer(self::getNameAsString().'_query');
				$this->checkSlowQuery(Debug::calcTimer(self::getNameAsString().'_query'), $query);
				foreach ($result_state as $key=>$values) {
					$this->result_all[$key]=$values;
				}
				$this->result_count=count($this->result_all);
				Cache::writeProtectedCacheArray(self::getNameAsString(), 'query-'.md5($query), $this->result_all);
				if (!isset(self::$stats['query_count'])) {
					self::$stats['query_count']=0;
				}
				self::$stats['query_count']++;
				$this->query_count=self::$stats['query_count'];
				$this->query_runtime=Debug::calcTimer(self::getNameAsString().'_query', 6);
			} catch (\PDOException $e) {
				$this->error=true;
				$this->error_message=$e->getMessage();
				$this->logPDOError('query', $e, $query);

				return [];
			}
		} else {
			$this->result_all=$result;
			$this->result_count=count($this->result_all);
		}

		return $this->result_all;
	}

	/**
	 * @param string $query
	 * @param int $expire
	 * @return DatabaseResult
	 */
	public function queryObject(string $query='', int $expire=0):DatabaseResult {
		return new DatabaseResult($this->query());
	}

	/**
	 *
	 * @return int
	 */
	public function rowCount():int {
		return $this->result_count;
	}

	/**
	 *
	 * @return array|null
	 */
	public function fetch():?array {
		if ($this->next()!==true) {
			return null;
		}

		return $this->result_all[$this->result_key];
	}

	/**
	 * @return DatabaseResult|null
	 */
	public function fetchObject():?DatabaseResult {
		if ($this->next()!==true) {
			return null;
		}

		return new DatabaseResult($this->result_all[$this->result_key]);
	}

	/**
	 * @return bool
	 */
	public function next():bool {
		if ($this->result_count==0) {
			return false;
		}

		if ($this->result_key===null) {
			$this->result_key=0;
		} elseif ($this->result_count>$this->result_key) {
			$this->result_key++;
		}

		$this->result=$this->result_all[$this->result_key];

		return true;
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
	 * @return array|null
	 */
	public function getResult():?array {
		if ($this->result!==[]) {
			return $this->result;
		}

		return null;
	}

	/**
	 *
	 * @return DatabaseResult|null
	 */
	public function getResultObject():?DatabaseResult {
		if ($this->result!==[]) {
			return new DatabaseResult($this->result);
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
	 * @return bool
	 */
	public function hasError():bool {
		return $this->error;
	}

	/**
	 * @return string
	 */
	public function getErrorMessage():string {
		return $this->error_message;
	}

	/**
	 * @return $this
	 */
	public function free():self {
		$this->PDO=null;
		$this->PDOStatement=null;
		$this->result_key=null;
		$this->result_count=0;
		$this->result=[];
		$this->result_all=[];
		$this->query='';
		$this->limitrows=[];
		$this->error=false;
		$this->error_message='';

		return $this;
	}

	/**
	 * @param string $error_type
	 * @param \PDOException $result
	 * @param string $query
	 * @return $this
	 */
	public function logPDOError(string $error_type, \PDOException $result, string $query=''):self {
		$trace=$result->getTrace();
		if (isset($trace[1])) {
			$error_file=$trace[1]['file'];
			$error_line=$trace[1]['line'];
		} else {
			$error_file='-';
			$error_line='-';
		}
		MessageStack::addMessage(self::getNameAsString(), $error_type, ['time'=>time(), 'query'=>$query, 'error'=>$result->getMessage(), 'error_code'=>$result->getCode(), 'error_file'=>$error_file, 'error_line'=>$error_line]);

		return $this;
	}

	/**
	 * @param int $runtime
	 * @param string $query
	 * @return bool
	 */
	public function checkSlowQuery(int $runtime, string $query):bool {
		if ($runtime>Settings::getFloatVar('database_slowruntime')) {
			MessageStack::addMessage(self::getNameAsString(), 'slowruntime', ['time'=>time(), 'line'=>__LINE__, 'function'=>__FUNCTION__, 'runtime'=>$runtime, 'query'=>$query]);

			return true;
		}

		return false;
	}

}

?>