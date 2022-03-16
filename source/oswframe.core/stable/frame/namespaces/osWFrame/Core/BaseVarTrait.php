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

trait BaseVarTrait {

	/**
	 * Array zum Speichern der Variablen.
	 *
	 * @var array|null
	 */
	protected ?array $vars=null;

	/**
	 * @return bool
	 */
	public function initVars():bool {
		if ($this->vars===null) {
			$this->clearVars();
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function resetVars():bool {
		$this->vars=null;

		return true;
	}

	/**
	 * @return bool
	 */
	public function clearVars():bool {
		$this->vars=[];

		return true;
	}

	/**
	 * @return bool
	 */
	public function isVarsLoaded():bool {
		if ($this->vars!==null) {
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
	public function setBoolVar(string $name, bool $value):bool {
		$this->initVars();
		$this->vars[$name]=$value;

		return true;
	}

	/**
	 * Setzt eine Variable vom Typ String.
	 *
	 * @param string $name
	 * @param string $value
	 * @return bool
	 */
	public function setStringVar(string $name, string $value):bool {
		$this->initVars();
		$this->vars[$name]=$value;

		return true;
	}

	/**
	 * Setzt eine Variable vom Typ Int.
	 *
	 * @param string $name
	 * @param int $value
	 * @return bool
	 */
	public function setIntVar(string $name, int $value):bool {
		$this->initVars();
		$this->vars[$name]=$value;

		return true;
	}

	/**
	 * Setzt eine Variable vom Typ Float.
	 *
	 * @param string $name
	 * @param float $value
	 * @return bool
	 */
	public function setFloatVar(string $name, float $value):bool {
		$this->initVars();
		$this->vars[$name]=$value;

		return true;
	}

	/**
	 * Setzt eine Variable vom Typ Array.
	 *
	 * @param string $name
	 * @param array $value
	 * @return bool
	 */
	public function setArrayVar(string $name, array $value):bool {
		$this->initVars();
		$this->vars[$name]=$value;

		return true;
	}

	/**
	 * @param string $name
	 * @param object $value
	 * @return bool
	 */
	public function setObjectVar(string $name, object $value):bool {
		$this->initVars();
		$this->vars[$name]=$value;

		return true;
	}

	/**
	 * Gibt eine Variable vom Typ Bool zurück.
	 * Existiert die Variable nicht, wird NULL zurückgeliefert.
	 *
	 * @param string $name
	 * @return bool|null
	 */
	public function getBoolVar(string $name):?bool {
		if ((strlen($name)>0)&&(isset($this->vars[$name]))) {
			return $this->vars[$name];
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
	public function getStringVar(string $name):?string {
		if ((strlen($name)>0)&&(isset($this->vars[$name]))) {
			return $this->vars[$name];
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
	public function getIntVar(string $name):?int {
		if ((strlen($name)>0)&&(isset($this->vars[$name]))) {
			return $this->vars[$name];
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
	public function getFloatVar(string $name):?float {
		if ((strlen($name)>0)&&(isset($this->vars[$name]))) {
			return $this->vars[$name];
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
	public function getArrayVar(string $name):?array {
		if ((strlen($name)>0)&&(isset($this->vars[$name]))) {
			return $this->vars[$name];
		}

		return null;
	}

	/**
	 * @param string $name
	 * @return object|null
	 */
	public function getObjectVar(string $name):?object {
		if ((strlen($name)>0)&&(isset($this->vars[$name]))) {
			return $this->vars[$name];
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
	public function getVarType(string $name):?string {
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

	/**
	 * @param array|null $vars
	 */
	public function setVars(?array $vars):void {
		$this->vars=$vars;
	}

	/**
	 * @return array|null
	 */
	public function getVars():?array {
		return $this->vars;
	}

}

?>