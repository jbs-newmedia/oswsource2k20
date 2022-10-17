<?php

/**
 * This file is part of the VIS2:Manager package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package VIS2:Manager
 * @link https://oswframe.com
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

namespace osWFrame\Api\Importer;

use osWFrame\Api\BaseReturnTrait;
use osWFrame\Core\BaseConnectionTrait;
use osWFrame\Core\BaseStaticTrait;

class Database {

	use BaseStaticTrait;
	use BaseConnectionTrait;
	use BaseReturnTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=2;

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
	private const CLASS_EXTRA_VERSION='beta';

	/**
	 * @var array
	 */
	protected array $json=[];

	/**
	 * @var array
	 */
	protected array $table_struct=[];

	/**
	 * JSON constructor.
	 */
	public function __construct(array $json=[]) {
		if ($json!=[]) {
			$this->setJSON($json);
		}
	}

	/**
	 * @param array $json
	 * @return $this
	 */
	public function setJSON(array $json):self {
		$this->json=$json;

		return $this;
	}

	/**
	 * @return $this
	 */
	public function createStruct():self {
		foreach ($this->json as $key=>$values) {
			$this->createTableStruct($key, $values);
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	public function optimizeStruct():self {
		foreach ($this->table_struct as $table=>$fields) {
			foreach ($fields as $field_name=>$field) {
				$this->table_struct[$table][$field_name]['length']=$this->optimizeLength($field['type'], $field['length']);
			}
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function getStruct():array {
		return $this->table_struct;
	}

	/**
	 * @param string $table
	 * @param array $fields
	 * @return $this
	 */
	public function createTableStruct(string $table, array $fields):self {
		$table=$this->checkFieldName($table);
		if (!isset($table_struct[$table])) {
			$this->table_struct[$table]=[];
		}
		foreach ($fields as $key=>$values) {
			foreach ($values as $vkey=>$vvalue) {
				$vkey=$this->checkFieldName($vkey);
				if (!isset($table_struct[$table][$vkey])) {
					$this->table_struct[$table][$vkey]=[];
				}
				if (!isset($table_struct[$table][$vkey]['length'])) {
					$this->table_struct[$table][$vkey]['length']=strlen($vvalue);
				} elseif ($this->table_struct[$table][$vkey]['length']<strlen($vvalue)) {
					$this->table_struct[$table][$vkey]['length']=strlen($vvalue);
				}
				$this->table_struct[$table][$vkey]['type']=$this->getType($vvalue, $this->table_struct[$table][$vkey]['length']);
			}
		}

		return $this;
	}

	/**
	 * @param string $value
	 * @param int $length
	 * @return string
	 */
	private function getType(string $value, int $length):string {
		if (strval(intval($value))==$value) {
			if ($length==1) {
				return 'tinyint';
			}

			return 'int';
		}
		if (str_replace(',', '.', strval(floatval($value)))==$value) {
			return 'float';
		}
		if (strval($value)==$value) {
			if ($length>56000) {
				return 'mediumtext';
			} elseif ($length>256) {
				return 'text';
			}

			return 'varchar';
		}

		return 'undefined';
	}

	/**
	 * @param string $type
	 * @param int $length
	 * @return int
	 */
	private function optimizeLength(string $type, int $length):int {
		return $length;

		/*
		if ($type=='tinyint') {
			return 1;
		}
		if ($type=='int') {
			return 11;
		}
		if ($type=='varchar') {
			if ($length<=16) {
				return 32;
			}
			if ($length<=32) {
				return 64;
			}
			if ($length<=64) {
				return 128;
			}
			if ($length<=128) {
				return 256;
			}
		}
		if ($type=='text') {
			if ($length<=12000) {
				return 24000;
			}
			if ($length<=24000) {
				return 48000;
			}
			if ($length<=40000) {
				return 56000;
			}
		}
		if ($type=='mediumtext') {
			if ($length<=50000) {
				return 100000;
			}
			if ($length<=100000) {
				return 200000;
			}
			if ($length<=200000) {
				return 400000;
			}
		}
		*/

		return 0;
	}

	/**
	 * @param string $value
	 * @return string
	 */
	private function checkFieldName(string $value):string {
		return str_replace(['ß'], ['ss'], $value);
		#return str_replace(['ä', 'ö', 'ü', 'Ä', 'Ö', 'Ü', 'ß'], ['ae', 'oe', 'ue', 'Ae', 'Oe', 'Ue', 'ss'], $value);
	}

}

?>