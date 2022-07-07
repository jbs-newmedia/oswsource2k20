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

namespace osWFrame\Core;

class DB {

	use BaseStaticTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=1;

	/**
	 * Minor-Version der Klasse.
	 */
	private const CLASS_MINOR_VERSION=2;

	/**
	 * Release-Version der Klasse.
	 */
	private const CLASS_RELEASE_VERSION=3;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	/**
	 * Speichert alle Verbindungen.
	 *
	 * @var array
	 */
	protected static array $connections=[];

	/**
	 * DB constructor.
	 */
	private function __construct() {

	}

	/**
	 * Verbindet die Datenbank.
	 *
	 * @param string $alias
	 * @return bool|null
	 */
	public static function connect(string $alias='default'):?bool {
		if ($alias=='') {
			$alias='default';
		}
		if (!isset(self::$connections[$alias])) {
			return null;
		}
		if (!isset(self::$connections[$alias]['con'])||!is_object(self::$connections[$alias]['con'])) {
			try {
				self::$connections[$alias]['con']=new \PDO(self::$connections[$alias]['dns'], self::$connections[$alias]['user'], self::$connections[$alias]['password']);
			} catch (\PDOException $e) {
				MessageStack::addMessage(self::getNameAsString(), 'connection', ['time'=>time(), 'code_code'=>$e->getCode(), 'error_message'=>$e->getMessage(), 'error_file'=>$e->getFile(), 'error_line'=>$e->getLine()]);

				return false;
			}
		}

		return true;
	}

	/**
	 * Fügt eine mySQL-Datenbankverbindung hinzu.
	 *
	 * @param string $host
	 * @param string $user
	 * @param string $password
	 * @param string $dbname
	 * @param string $charset
	 * @param string $alias
	 * @param int $port
	 * @return bool
	 */
	public static function addConnectionMYSQL(string $host, string $user, string $password, string $dbname, string $charset='utf8', string $alias='default', int $port=3306):bool {
		if ($alias=='') {
			$alias='default';
		}
		return self::addConnection('mysql:host='.$host.';dbname='.$dbname.';charset='.$charset.';port='.$port, $user, $password, $alias);
	}

	/**
	 * Fügt eine Datenbankverbindung hinzu.
	 *
	 * @param string $dns
	 * @param string $user
	 * @param string $password
	 * @param string $alias
	 * @return bool
	 */
	public static function addConnection(string $dns, string $user, string $password, string $alias='default'):bool {
		if ($alias=='') {
			$alias='default';
		}
		self::$connections[$alias]=['connected'=>false, 'dns'=>$dns, 'user'=>$user, 'password'=>$password, 'con'=>null];

		return true;
	}

	/**
	 * Gibt die Verbindung zur Datenbank zurück
	 *
	 * @param string $alias
	 * @return object|null
	 */
	public static function getConnection(string $alias='default'):?\PDO {
		if ($alias=='') {
			$alias='default';
		}
		if (!isset(self::$connections[$alias])) {
			return null;
		}

		return self::$connections[$alias]['con'];
	}

}

?>