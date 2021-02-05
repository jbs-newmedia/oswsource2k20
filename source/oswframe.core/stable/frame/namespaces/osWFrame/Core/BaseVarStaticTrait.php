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

trait BaseVarStaticTrait {

	/**
	 * Array zum Speichern der Variablen.
	 *
	 * @var array|null
	 */
	private static ?array $vars=null;

	/**
	 * @return bool
	 */
	public static function initVars():bool {
		if (self::$vars===null) {
			self::clearVars();
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public static function resetVars():bool {
		self::$vars=null;

		return true;
	}

	/**
	 * @return bool
	 */
	public static function clearVars():bool {
		self::$vars=[];

		return true;
	}

	/**
	 * @return bool
	 */
	public static function isVarsLoaded():bool {
		if (self::$vars!==null) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Setzt eine Variable vom Typ Bool.
	 *
	 * @param string $name
	 * @param bool $value
	 * @return bool
	 */
	public static function setBoolVar(string $name, bool $value):bool {
		self::initVars();
		self::$vars[$name]=$value;

		return true;
	}

	/**
	 * Setzt eine Variable vom Typ String.
	 *
	 * @param string $name
	 * @param string $value
	 * @return bool
	 */
	public static function setStringVar(string $name, string $value):bool {
		self::initVars();
		self::$vars[$name]=$value;

		return true;
	}

	/**
	 * *Setzt eine Variable vom Typ Int.
	 *
	 * @param string $name
	 * @param int $value
	 * @return bool
	 */
	public static function setIntVar(string $name, int $value):bool {
		self::initVars();
		self::$vars[$name]=$value;

		return true;
	}

	/**
	 * *Setzt eine Variable vom Typ Float.
	 *
	 * @param string $name
	 * @param float $value
	 * @return bool
	 */
	public static function setFloatVar(string $name, float $value):bool {
		self::initVars();
		self::$vars[$name]=$value;

		return true;
	}

	/**
	 * *Setzt eine Variable vom Typ Array.
	 *
	 * @param string $name
	 * @param array $value
	 * @return bool
	 */
	public static function setArrayVar(string $name, array $value):bool {
		self::initVars();
		self::$vars[$name]=$value;

		return true;
	}

	/**
	 * Gibt eine Variable vom Typ Bool zurück.
	 * Existiert die Variable nicht, wird NULL zurückgeliefert.
	 *
	 * @param string $name
	 * @return bool|null
	 */
	public static function getBoolVar(string $name):?bool {
		if ((strlen($name)>0)&&(isset(self::$vars[$name]))) {
			return self::$vars[$name];
		}

		return null;
	}

	/**
	 * Gibt eine Variable vom Typ String zurück.
	 * Existiert die Variable nicht, wird NULL zurückgeliefert.
	 *
	 * @param string $name
	 * @return string|null
	 */
	public static function getStringVar(string $name):?string {
		if ((strlen($name)>0)&&(isset(self::$vars[$name]))) {
			return self::$vars[$name];
		}

		return null;
	}

	/**
	 * Gibt eine Variable vom Typ Int zurück.
	 * Existiert die Variable nicht, wird NULL zurückgeliefert.
	 *
	 * @param string $name
	 * @return int|null
	 */
	public static function getIntVar(string $name):?int {
		if ((strlen($name)>0)&&(isset(self::$vars[$name]))) {
			return self::$vars[$name];
		}

		return null;
	}

	/**
	 * Gibt eine Variable vom Typ Float zurück.
	 * Existiert die Variable nicht, wird NULL zurückgeliefert.
	 *
	 * @param string $name
	 * @return float|null
	 */
	public static function getFloatVar(string $name):?float {
		if ((strlen($name)>0)&&(isset(self::$vars[$name]))) {
			return self::$vars[$name];
		}

		return null;
	}

	/**
	 * Gibt eine Variable vom Typ Array zurück.
	 * Existiert die Variable nicht, wird NULL zurückgeliefert.
	 *
	 * @param string $name
	 * @return array|null
	 */
	public static function getArrayVar(string $name):?array {
		if ((strlen($name)>0)&&(isset(self::$vars[$name]))) {
			return self::$vars[$name];
		}

		return null;
	}

	/**
	 * Gibt den Typ einer Variablen zurück.
	 * Existiert die Variable nicht, wird NULL zurückgeliefert.
	 *
	 * @param string $name
	 * @return string|null
	 */
	public static function getVarType(string $name):?string {
		switch (gettype($name)) {
			case 'bool':
				return 'bool';
				break;
			case 'integer':
				return 'int';
				break;
			case 'array':
				return 'array';
				break;
			case 'double':
				return 'float';
				break;
			case 'string':
				return 'string';
				break;
			default:
				return null;
				break;
		}
	}

}

?>