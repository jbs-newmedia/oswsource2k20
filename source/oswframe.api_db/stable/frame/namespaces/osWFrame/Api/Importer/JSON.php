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

class JSON {

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
				$this->table_struct[$table][$field_name]['column_length']=$this->optimizeLength($field['column_type'], $field['column_length']);
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
		$QgetData=self::getConnection();
		$QgetData->prepare('SELECT * FROM :table_api_database_column: WHERE table_name=:table_name: ORDER BY column_position ASC');
		$QgetData->bindTable(':table_api_database_column:', 'api_database_column');
		$QgetData->bindString(':table_name:', $table);
		foreach ($QgetData->query() as $column) {
			$this->table_struct[$table][$column['column_name']]=$column;
		}

		$table=$this->checkFieldName($table);
		if (!isset($this->table_struct[$table])) {
			$this->table_struct[$table]=[];
		}
		foreach ($fields as $values) {
			foreach ($values as $vkey=>$vvalue) {
				if ($vvalue==[]) {
					$vvalue='';
				}
				$vkey=$this->checkFieldName($vkey);
				if (!isset($this->table_struct[$table][$vkey])) {
					$this->table_struct[$table][$vkey]=[];
				}
				if ((!isset($this->table_struct[$table][$vkey]['column_convert']))||($this->table_struct[$table][$vkey]['column_convert']=='')) {
					if (!isset($this->table_struct[$table][$vkey]['column_length'])) {
						$this->table_struct[$table][$vkey]['column_length']=strlen($vvalue);
					}
					if ($this->table_struct[$table][$vkey]['column_length']<strlen($vvalue)) {
						$this->table_struct[$table][$vkey]['column_length']=strlen($vvalue);
					}
					$this->table_struct[$table][$vkey]['column_type']=$this->getType($vvalue, $this->table_struct[$table][$vkey]['column_length']);
				}
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
		return $value;

		return str_replace(['ß'], ['ss'], $value);
		#return str_replace(['ä', 'ö', 'ü', 'Ä', 'Ö', 'Ü', 'ß'], ['ae', 'oe', 'ue', 'Ae', 'Oe', 'Ue', 'ss'], $value);
	}

	/**
	 * @param array $struct
	 * @return $this
	 */
	public function clearData(array $struct):self {
		foreach ($this->json as $table=>$table_elements) {
			if (isset($struct[$table])) {
				$QclearData=self::getConnection();
				$QclearData->prepare('DELETE FROM :table: WHERE 1');
				$QclearData->bindTable(':table:', 'pluss_'.$table);
				$QclearData->execute();
			}
		}

		return $this;
	}

	/**
	 * @param array $struct
	 * @param array $added_values
	 * @return $this
	 */
	public function writeData(array $struct, array $added_values=[]):self {
		foreach ($this->json as $table=>$table_elements) {
			if (isset($struct[$table])) {
				foreach ($table_elements as $row_elements) {
					if ($added_values!=[]) {
						foreach ($added_values as $key=>$value) {
							$row_elements[$key]=$value;
						}
					}

					$row_fields=array_keys($row_elements);
					$QinsertData=self::getConnection();
					$QinsertData->prepare('INSERT INTO :table: (:fields:) VALUE (:values:)');
					$QinsertData->bindTable(':table:', 'pluss_'.$table);
					$QinsertData->bindRaw(':fields:', implode(', ', $row_fields));
					$QinsertData->bindRaw(':values:', ':'.implode(':, :', $row_fields).':');
					foreach ($row_elements as $row_key=>$row_value) {
						if ($row_value==[]) {
							$row_value='';
						}
						switch ($struct[$table]['columns'][$row_key]['column_type']) {
							case 'int':
							case 'tinyint':
								$QinsertData->bindInt(':'.$row_key.':', intval($row_value));
								break;
							case 'float':
							case 'double':
								$QinsertData->bindFloat(':'.$row_key.':', str_replace(',', '.', strval(floatval($row_value))));
								break;
							default:
								$QinsertData->bindString(':'.$row_key.':', $row_value);
								break;
						}
					}
					$QinsertData->execute();
				}
			}
		}

		return $this;
	}

	/**
	 * @param array $struct
	 * @return $this
	 */
	public function updateData(array $struct):self {
		foreach ($this->json as $table=>$table_elements) {
			if (isset($struct[$table])) {
				foreach ($table_elements as $row_elements) {
					$row_fields=array_keys($row_elements);
					$index_row_key=array_key_first($row_elements);
					$index_row_value=$row_elements[$index_row_key];

					$QcheckData=self::getConnection();
					$QcheckData->prepare('SELECT * FROM :table: WHERE :index_row_key:=:index_row_value:');
					$QcheckData->bindTable(':table:', 'pluss_'.$table);
					$QcheckData->bindRaw(':index_row_key:', $index_row_key);
					switch ($struct[$table]['columns'][$index_row_key]['column_type']) {
						case 'int':
						case 'tinyint':
							$QcheckData->bindInt(':index_row_value:', $index_row_value);
							break;
						case 'float':
						case 'double':
							$QcheckData->bindFloat(':index_row_value:', str_replace(',', '.', strval(floatval($index_row_value))));
							break;
						default:
							$QcheckData->bindString(':index_row_value:', $index_row_value);
							break;
					}
					$QcheckData->execute();
					if ($QcheckData->rowCount()==1) {
						$row_fields_pairs=[];
						foreach ($row_fields as $key) {
							$row_fields_pairs[]=$key.'=:'.$key.':';
						}
						$QupdateData=self::getConnection();
						$QupdateData->prepare('UPDATE :table: SET :fields_values: WHERE :index_row_key:=:index_row_value:');
						$QupdateData->bindTable(':table:', 'pluss_'.$table);
						$QupdateData->bindRaw(':index_row_key:', $index_row_key);
						switch ($struct[$table]['columns'][$index_row_key]['column_type']) {
							case 'int':
							case 'tinyint':
								$QupdateData->bindInt(':index_row_value:', $index_row_value);
								break;
							case 'float':
							case 'double':
								$QupdateData->bindFloat(':index_row_value:', str_replace(',', '.', strval(floatval($index_row_value))));
								break;
							default:
								$QupdateData->bindString(':index_row_value:', $index_row_value);
								break;
						}
						$QupdateData->bindRaw(':fields_values:', implode(', ', $row_fields_pairs));
						foreach ($row_elements as $row_key=>$row_value) {
							if ($row_value==[]) {
								$row_value='';
							}
							switch ($struct[$table]['columns'][$row_key]['column_type']) {
								case 'int':
								case 'tinyint':
									$QupdateData->bindInt(':'.$row_key.':', $row_value);
									break;
								case 'float':
								case 'double':
									$QupdateData->bindFloat(':'.$row_key.':', str_replace(',', '.', strval(floatval($row_value))));
									break;
								default:
									$QupdateData->bindString(':'.$row_key.':', $row_value);
									break;
							}
						}
						$QupdateData->execute();
					} else {
						$QinsertData=self::getConnection();
						$QinsertData->prepare('INSERT INTO :table: (:fields:) VALUE (:values:)');
						$QinsertData->bindTable(':table:', 'pluss_'.$table);
						$QinsertData->bindRaw(':fields:', implode(', ', $row_fields));
						$QinsertData->bindRaw(':values:', ':'.implode(':, :', $row_fields).':');
						foreach ($row_elements as $row_key=>$row_value) {
							if ($row_value==[]) {
								$row_value='';
							}
							switch ($struct[$table]['columns'][$row_key]['column_type']) {
								case 'int':
								case 'tinyint':
									$QinsertData->bindInt(':'.$row_key.':', $row_value);
									break;
								case 'float':
								case 'double':
									$QinsertData->bindFloat(':'.$row_key.':', str_replace(',', '.', strval(floatval($row_value))));
									break;
								default:
									$QinsertData->bindString(':'.$row_key.':', $row_value);
									break;
							}
						}
						$QinsertData->execute();
					}
				}
			}
		}

		return $this;
	}

	/**
	 * @param array $struct
	 * @return $this
	 */
	public function deleteData(array $struct):self {
		foreach ($this->json as $table=>$table_elements) {
			if (isset($struct[$table])) {
				foreach ($table_elements as $row_elements) {
					$row_fields=array_keys($row_elements);
					$index_row_key=array_key_first($row_elements);
					$index_row_value=$row_elements[$index_row_key];

					$QdeleteData=self::getConnection();
					$QdeleteData->prepare('DELETE FROM :table: WHERE :index_row_key:=:index_row_value:');
					$QdeleteData->bindTable(':table:', 'pluss_'.$table);
					$QdeleteData->bindRaw(':index_row_key:', $index_row_key);
					switch ($struct[$table]['columns'][$index_row_key]['column_type']) {
						case 'int':
						case 'tinyint':
							$QdeleteData->bindInt(':index_row_value:', $index_row_value);
							break;
						case 'float':
						case 'double':
							$QdeleteData->bindFloat(':index_row_value:', str_replace(',', '.', strval(floatval($index_row_value))));
							break;
						default:
							$QdeleteData->bindString(':index_row_value:', $index_row_value);
							break;
					}
					$QdeleteData->execute();
				}
			}
		}

		return $this;
	}

}

?>