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

class DatabaseResult {

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
	public ?object $result=null;

	/**
	 * DatabaseResult constructor.
	 *
	 * @param array $result
	 */
	function __construct(array $result) {
		$this->result=$result;
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

}

?>