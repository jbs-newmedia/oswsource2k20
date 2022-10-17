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

class DBStruct {

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
	 * @var int
	 */
	protected int $user_id=0;

	/**
	 * @var int
	 */
	protected int $time=0;

	/**
	 * @var array|null
	 */
	protected ?array $data_struct=null;

	/**
	 * DBStruct constructor.
	 *
	 * @param int $user_id
	 * @param int $time
	 */
	public function __construct(int $user_id=0, int $time=0) {
		if ($time==0) {
			$time=time();
		}
		$this->user_id=$user_id;
		$this->time=$time;
	}

	/**
	 * @return int
	 */
	public function getTime():int {
		return $this->time;
	}

	/**
	 * @return int
	 */
	public function getUserId():int {
		return $this->user_id;
	}

	/**
	 * @param string $input
	 * @return string
	 */
	public function cleanName(string $name):string {
		return preg_replace('/[^a-z0-9-_]/', '', strtolower($name));
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function checkName(string $name):bool {
		if (strlen($name)<2) {
			$this->setError(true);
			$this->setErrorMessage('table "'.$name.'": name to short.');

			return false;
		}

		return true;
	}

	/**
	 * @param string $storage_engine
	 * @return string
	 */
	public function correctStorageEngine(string $storage_engine=''):string {
		switch ($storage_engine) {
			default:
				return osWFrame\Settings::getStringVar('database_engine');
		}
	}

	/**
	 * @param string $collation
	 * @return string
	 */
	public function correctCollation(string $collation=''):string {
		switch ($collation) {
			default:
				return osWFrame\Settings::getStringVar('database_collation');
		}
	}

	/**
	 * @param string $table_name
	 * @return bool
	 */
	public function checkTableName(string $table_name):bool {
		if ($table_name!==$this->cleanName($table_name)) {
			$this->setError(true);
			$this->setErrorMessage('table "'.$table_name.'": name not allowed.');

			return false;
		}
		if ($this->checkName($table_name)!==true) {
			return false;
		}

		return true;
	}

	/**
	 * @param string $table_name
	 * @return bool
	 */
	public function existsTableName(string $table_name):bool {
		$QcheckData=self::getConnection();
		$QcheckData->prepare('SELECT * FROM :table_api_database_table: WHERE table_name=:table_name:');
		$QcheckData->bindTable(':table_api_database_table:', 'api_database_table');
		$QcheckData->bindString(':table_name:', $table_name);
		$QcheckData->execute();
		if ($QcheckData->rowCount()==1) {
			return true;
		}

		return false;
	}

	/**
	 * @param string $column_name
	 * @return bool
	 */
	public function checkColumnName(string $column_name):bool {
		if ($column_name!==$this->cleanName($column_name)) {
			$this->setError(true);
			$this->setErrorMessage('column "'.$column_name.'": name not allowed.');

			return false;
		}
		if ($this->checkName($column_name)!==true) {
			return false;
		}

		return true;
	}

	/**
	 * @param string $collation
	 * @return bool
	 */
	public function checkColumnType(string $type):bool {
		if (in_array($type, ['varchar', 'int', 'tinyint', 'double', 'text'])) {
			return true;
		}

		return false;
	}

	/**
	 * @param string $options
	 * @return bool
	 */
	public function checkColumnOptions(string $options):bool {
		if (in_array($options, ['primary', 'index', ''])) {
			return true;
		}

		return false;
	}

	/**
	 * @param string $type
	 * @param string $collation
	 * @return bool
	 */
	public function checkColumnCollation(string $type, string $collation):bool {
		if (in_array($type, ['int', 'tinyint', 'double'])) {
			if (in_array($collation, ['unsigned', ''])) {
				return true;
			}

			return false;
		}

		return true;
	}

	/**
	 * @param string $table_name
	 * @param string $column_name
	 * @return bool
	 */
	public function existsColumnName(string $table_name, string $column_name):bool {
		$QcheckData=self::getConnection();
		$QcheckData->prepare('SELECT * FROM :table_api_database_column: WHERE table_name=:table_name: AND column_name=:column_name:');
		$QcheckData->bindTable(':table_api_database_column:', 'api_database_column');
		$QcheckData->bindString(':table_name:', $table_name);
		$QcheckData->bindString(':column_name:', $column_name);
		$QcheckData->execute();
		if ($QcheckData->rowCount()==1) {
			return true;
		}

		return false;
	}

	/**
	 * @param string $name
	 * @param string $storage_engine
	 * @param string $collation
	 * @param string $table_comment
	 * @return bool
	 */
	public function createTable(string $table_name, string $storage_engine='', string $collation='', string $comment=''):bool {
		if ($this->checkTableName($table_name)!==true) {
			return false;
		}
		if ($this->existsTableName($table_name)!==true) {
			$QinsertData=self::getConnection();
			$QinsertData->prepare('INSERT INTO :table_api_database_table: (table_name, table_storage_engine, table_collation, table_comment, table_create_time, table_create_user_id, table_update_time, table_update_user_id) VALUES (:table_name:, :table_storage_engine:, :table_collation:, :table_comment:, :table_create_time:, :table_create_user_id:, :table_update_time:, :table_update_user_id:)');
			$QinsertData->bindTable(':table_api_database_table:', 'api_database_table');
			$QinsertData->bindString(':table_name:', $table_name);
			$QinsertData->bindString(':table_storage_engine:', $this->correctStorageEngine($storage_engine));
			$QinsertData->bindString(':table_collation:', $this->correctCollation($collation));
			$QinsertData->bindString(':table_comment:', $comment);
			$QinsertData->bindInt(':table_create_time:', $this->getTime());
			$QinsertData->bindInt(':table_create_user_id:', $this->getUserId());
			$QinsertData->bindInt(':table_update_time:', $this->getTime());
			$QinsertData->bindInt(':table_update_user_id:', $this->getUserId());
			$QinsertData->execute();

			$this->setSuccessMessage('table "'.$table_name.'": created successfully.');

			return true;
		} else {
			$this->setError(true);
			$this->setErrorMessage('table "'.$table_name.'": exists.');

			return false;
		}
	}

	/**
	 * @param string $table_name
	 * @param string $storage_engine
	 * @param string $collation
	 * @param string $table_comment
	 * @return bool
	 */
	public function updateTable(string $table_name, string $storage_engine='', string $collation='', string $comment=''):bool {
		if ($this->checkTableName($table_name)!==true) {
			return false;
		}
		if ($this->existsTableName($table_name)===true) {
			$QupdateData=self::getConnection();
			$QupdateData->prepare('UPDATE :table_api_database_table: SET table_storage_engine=:table_storage_engine:, table_collation=:table_collation:, table_comment=:table_comment:, table_update_time=:table_update_time:, table_update_user_id=:table_update_user_id: WHERE table_name=:table_name:');
			$QupdateData->bindTable(':table_api_database_table:', 'api_database_table');
			$QupdateData->bindString(':table_storage_engine:', $this->correctStorageEngine($storage_engine));
			$QupdateData->bindString(':table_collation:', $this->correctCollation($collation));
			$QupdateData->bindString(':table_comment:', $comment);
			$QupdateData->bindInt(':table_update_time:', $this->getTime());
			$QupdateData->bindInt(':table_update_user_id:', $this->getUserId());
			$QupdateData->bindString(':table_name:', $table_name);
			$QupdateData->execute();

			$this->setSuccessMessage('table "'.$table_name.'": updated successfully.');

			return true;
		} else {
			$this->setError(true);
			$this->setErrorMessage('table "'.$table_name.'": doesn\'t exists.');

			return false;
		}
	}

	/**
	 * @param string $table_name
	 * @return bool
	 */
	public function deleteTable(string $table_name):bool {
		if ($this->checkTableName($table_name)!==true) {
			return false;
		}
		if ($this->existsTableName($table_name)===true) {
			$QdeleteData=self::getConnection();
			$QdeleteData->prepare('DELETE FROM :table_api_database_table: WHERE table_name=:table_name:');
			$QdeleteData->bindTable(':table_api_database_table:', 'api_database_table');
			$QdeleteData->bindString(':table_name:', $table_name);
			$QdeleteData->execute();

			$QdeleteData=self::getConnection();
			$QdeleteData->prepare('DELETE FROM :table_api_database_column: WHERE table_name=:table_name:');
			$QdeleteData->bindTable(':table_api_database_column:', 'api_database_column');
			$QdeleteData->bindString(':table_name:', $table_name);
			$QdeleteData->execute();

			$this->setSuccessMessage('table "'.$table_name.'": deleted successfully.');

			return true;
		} else {
			$this->setError(true);
			$this->setErrorMessage('table "'.$table_name.'": doesn\'t exists.');

			return false;
		}
	}

	/**
	 * @param string $table_name
	 * @param string $name
	 * @param string $type
	 * @param int $length
	 * @param int $position
	 * @param string $collation
	 * @param string $options
	 * @param int $setnull
	 * @param int $autoincrement
	 * @return bool
	 */
	public function createColumn(string $table_name, string $name, string $type, int $length, int $position=0, string $collation='', string $options='', int $setnull=0, int $autoincrement=0):bool {
		if ($this->checkTableName($table_name)!==true) {
			return false;
		}
		if ($this->existsTableName($table_name)===true) {
			if ($this->checkColumnName($name)!==true) {
				return false;
			}
			if ($this->existsColumnName($table_name, $name)!==true) {
				if ($this->checkColumnType($type)!==true) {
					$this->setError(true);
					$this->setErrorMessage('table "'.$table_name.', column "'.$name.'": type error.');

					return false;
				}

				if ($this->checkColumnOptions($options)!==true) {
					$this->setError(true);
					$this->setErrorMessage('table "'.$table_name.', column "'.$name.'": options error.');

					return false;
				}

				if ($this->checkColumnCollation($type, $collation)!==true) {
					$this->setError(true);
					$this->setErrorMessage('table "'.$table_name.', column "'.$name.'": collation error.');

					return false;
				}

				$QinsertData=self::getConnection();
				$QinsertData->prepare('INSERT INTO :table_api_database_column: (table_name, column_name, column_position, column_type, column_length, column_collation, column_options, column_setnull, column_autoincrement, column_create_time, column_create_user_id, column_update_time, column_update_user_id) VALUES (:table_name:, :column_name:, :column_position:, :column_type:, :column_length:, :column_collation:, :column_options:, :column_setnull:, :column_autoincrement:, :column_create_time:, :column_create_user_id:, :column_update_time:, :column_update_user_id:)');
				$QinsertData->bindTable(':table_api_database_column:', 'api_database_column');
				$QinsertData->bindString(':table_name:', $table_name);
				$QinsertData->bindString(':column_name:', $name);
				$QinsertData->bindInt(':column_position:', $position);
				$QinsertData->bindString(':column_type:', $type);
				$QinsertData->bindInt(':column_length:', $length);
				if (in_array($type, ['varchar', 'text'])) {
					$QinsertData->bindString(':column_collation:', $this->correctCollation($collation));
				} else {
					$QinsertData->bindString(':column_collation:', $collation);
				}
				$QinsertData->bindString(':column_options:', $options);
				$QinsertData->bindInt(':column_setnull:', $setnull);
				$QinsertData->bindInt(':column_autoincrement:', $autoincrement);
				$QinsertData->bindInt(':column_create_time:', $this->getTime());
				$QinsertData->bindInt(':column_create_user_id:', $this->getUserId());
				$QinsertData->bindInt(':column_update_time:', $this->getTime());
				$QinsertData->bindInt(':column_update_user_id:', $this->getUserId());
				$QinsertData->execute();

				$this->setSuccessMessage('table "'.$table_name.', column "'.$name.'": created successfully.');

				return true;
			} else {
				$this->setError(true);
				$this->setErrorMessage('table "'.$table_name.', column "'.$name.'": exists.');

				return false;
			}
		} else {
			$this->setError(true);
			$this->setErrorMessage('table "'.$table_name.'": doesn\'t exists.');

			return false;
		}
	}

	/**
	 * @param string $table_name
	 * @param string $name
	 * @param string $type
	 * @param int $length
	 * @param int $position
	 * @param string $collation
	 * @param string $options
	 * @param int $setnull
	 * @param int $autoincrement
	 * @return bool
	 */
	public function updateColumn(string $table_name, string $name, string $type, int $length, int $position=0, string $collation='', string $options='', int $setnull=0, int $autoincrement=0):bool {
		if ($this->checkTableName($table_name)!==true) {
			return false;
		}
		if ($this->existsTableName($table_name)===true) {
			if ($this->checkColumnName($name)!==true) {
				return false;
			}
			if ($this->existsColumnName($table_name, $name)===true) {
				if ($this->checkColumnType($type)!==true) {
					$this->setError(true);
					$this->setErrorMessage('table "'.$table_name.', column "'.$name.'": type error.');

					return false;
				}

				if ($this->checkColumnOptions($options)!==true) {
					$this->setError(true);
					$this->setErrorMessage('table "'.$table_name.', column "'.$name.'": options error.');

					return false;
				}

				if ($this->checkColumnCollation($type, $collation)!==true) {
					$this->setError(true);
					$this->setErrorMessage('table "'.$table_name.', column "'.$name.'": collation error.');

					return false;
				}

				$QupdateData=self::getConnection();
				$QupdateData->prepare('UPDATE :table_api_database_column: SET column_position=:column_position:, column_type=:column_type:, column_length=:column_length:, column_collation=:column_collation:, column_options=:column_options:, column_setnull=:column_setnull:, column_autoincrement=:column_autoincrement:, column_update_time=:column_update_time:, column_update_user_id=:column_update_user_id: WHERE table_name=:table_name: AND column_name=:column_name:');
				$QupdateData->bindTable(':table_api_database_column:', 'api_database_column');
				$QupdateData->bindString(':table_name:', $table_name);
				$QupdateData->bindString(':column_name:', $name);
				$QupdateData->bindInt(':column_position:', $position);
				$QupdateData->bindString(':column_type:', $type);
				$QupdateData->bindInt(':column_length:', $length);
				if (in_array($type, ['varchar', 'text'])) {
					$QupdateData->bindString(':column_collation:', $this->correctCollation($collation));
				} else {
					$QupdateData->bindString(':column_collation:', $collation);
				}
				$QupdateData->bindString(':column_options:', $options);
				$QupdateData->bindInt(':column_setnull:', $setnull);
				$QupdateData->bindInt(':column_autoincrement:', $autoincrement);
				$QupdateData->bindInt(':column_update_time:', $this->getTime());
				$QupdateData->bindInt(':column_update_user_id:', $this->getUserId());
				$QupdateData->execute();

				$this->setSuccessMessage('table "'.$table_name.', column "'.$name.'": updated successfully.');

				return true;
			} else {
				$this->setError(true);
				$this->setErrorMessage('table "'.$table_name.', column "'.$name.'": doesn\'t exists.');

				return false;
			}
		} else {
			$this->setError(true);
			$this->setErrorMessage('table "'.$table_name.'": doesn\'t exists.');

			return false;
		}
	}

	/**
	 * @param string $table_name
	 * @param string $name
	 * @return bool
	 */
	public function deleteColumn(string $table_name, string $name):bool {
		if ($this->checkTableName($table_name)!==true) {
			return false;
		}
		if ($this->existsTableName($table_name)===true) {
			if ($this->checkColumnName($name)!==true) {
				return false;
			}
			if ($this->existsColumnName($table_name, $name)===true) {
				$QdeleteData=self::getConnection();
				$QdeleteData->prepare('DELETE FROM :table_api_database_column: WHERE table_name=:table_name: AND column_name=:column_name:');
				$QdeleteData->bindTable(':table_api_database_column:', 'api_database_column');
				$QdeleteData->bindString(':table_name:', $table_name);
				$QdeleteData->bindString(':column_name:', $name);
				$QdeleteData->execute();

				$this->setSuccessMessage('table "'.$table_name.', column "'.$name.'": deleted successfully.');

				return true;
			} else {
				$this->setError(true);
				$this->setErrorMessage('table "'.$table_name.', column "'.$name.'": doesn\'t exists.');

				return false;
			}
		} else {
			$this->setError(true);
			$this->setErrorMessage('table "'.$table_name.'": doesn\'t exists.');

			return false;
		}
	}

	/**
	 * @return array
	 */
	public function getStruct():array {
		if ($this->data_struct==null) {
			$this->data_struct=[];
			$QcheckDataTable=self::getConnection();
			$QcheckDataTable->prepare('SELECT * FROM :table_api_database_table: WHERE 1 ORDER BY table_name ASC');
			$QcheckDataTable->bindTable(':table_api_database_table:', 'api_database_table');
			foreach ($QcheckDataTable->query() as $table) {
				$this->data_struct[$table['table_name']]['details']=$table;
				$this->data_struct[$table['table_name']]['columns']=[];

				$QcheckDataColumn=self::getConnection();
				$QcheckDataColumn->prepare('SELECT * FROM :table_api_database_column: WHERE table_name=:tablename: ORDER BY column_position ASC, column_name ASC');
				$QcheckDataColumn->bindTable(':table_api_database_column:', 'api_database_column');
				$QcheckDataColumn->bindString(':tablename:', $table['table_name']);
				foreach ($QcheckDataColumn->query() as $column) {
					$this->data_struct[$table['table_name']]['columns'][$column['column_name']]=$column;
				}
			}
		}

		return $this->data_struct;
	}

	/**
	 * @return bool
	 */
	public function runStruct():bool {
		foreach ($this->getStruct() as $table_details) {
			$QreadData=self::getConnection();
			$QreadData->prepare('SHOW TABLE STATUS LIKE \':table:\'');
			$QreadData->bindTable(':table:', 'c_'.$table_details['details']['table_name']);
			if ($QreadData->exec()==0) {
				$key=[];
				$columns=[];
				foreach ($table_details['columns'] as $column_details) {
					if (in_array($column_details['column_options'], ['primary'])) {
						$key[]='PRIMARY KEY ('.$column_details['column_name'].')';
					}
					if (in_array($column_details['column_options'], ['index'])) {
						$key[]='KEY '.$column_details['column_name'].' ('.$column_details['column_name'].')';
					}
					$columns[]=$this->createColumnString($column_details);
				}
				$QwriteData=self::getConnection();
				$QwriteData->prepare('CREATE TABLE :table: (:column::key:) ENGINE=:engine: COLLATE=:charset: COMMENT=:comment:;');
				$QwriteData->bindTable(':table:', 'c_'.$table_details['details']['table_name']);
				$QwriteData->bindRaw(':column:', implode(', ', $columns));
				if ($key==[]) {
					$QwriteData->bindRaw(':key:', '');
				} else {
					$QwriteData->bindRaw(':key:', ', '.implode(', ', $key));
				}
				$QwriteData->bindRaw(':engine:', $table_details['details']['table_storage_engine']);
				$QwriteData->bindRaw(':charset:', $table_details['details']['table_collation']);
				$QwriteData->bindString(':comment:', $table_details['details']['table_comment']);
				$QwriteData->execute();
			} else {
				$table_data=$QreadData->fetch();
				if ($table_data['Collation']!=$table_details['table_collation']) {
					$QwriteData=self::getConnection();
					$QwriteData->prepare('ALTER TABLE :table: COLLATE=:charset:;');
					$QwriteData->bindTable(':table:', 'c_'.$table_details['details']['table_name']);
					$QwriteData->bindRaw(':charset:', $table_details['details']['table_collation']);
					$QwriteData->execute();
				}
				if ($table_data['Engine']!=$table_details['table_storage_engine']) {
					$QwriteData=self::getConnection();
					$QwriteData->prepare('ALTER TABLE :table: ENGINE=:engine:;');
					$QwriteData->bindTable(':table:', 'c_'.$table_details['details']['table_name']);
					$QwriteData->bindRaw(':engine:', $table_details['details']['table_storage_engine']);
					$QwriteData->execute();
				}
				if ($table_data['Comment']!=$table_details['table_storage_engine']) {
					$QwriteData=self::getConnection();
					$QwriteData->prepare('ALTER TABLE :table: COMMENT=:comment:;');
					$QwriteData->bindTable(':table:', 'c_'.$table_details['details']['table_name']);
					$QwriteData->bindString(':comment:', $table_details['details']['table_comment']);
					$QwriteData->execute();
				}

				$columns=[];
				$QreadData=self::getConnection();
				$QreadData->prepare('SHOW FULL COLUMNS FROM :table:');
				$QreadData->bindTable(':table:', 'c_'.$table_details['details']['table_name']);
				foreach ($QreadData->query() as $column) {
					$columns[$column['Field']]=$column;
				}

				$column_name_last='';
				foreach ($table_details['columns'] as $column_name=>$column) {

					if (!isset($columns[$column_name])) {
						$QwriteData=self::getConnection();
						$QwriteData->prepare('ALTER TABLE `:table:` ADD :column_data: :position:');
						$QwriteData->bindTable(':table:', 'c_'.$table_details['details']['table_name']);
						$QwriteData->bindRaw(':column_name:', $column_name);
						$QwriteData->bindRaw(':column_data:', $this->createColumnString($column));
						if ($column_name_last=='') {
							$QwriteData->bindRaw(':position:', 'FIRST');
						} else {
							$QwriteData->bindRaw(':position:', 'AFTER `'.$column_name_last.'`');
						}
						$QwriteData->execute();
					} elseif ($this->createColumnString($column)!==$this->createColumnStringFromMYSQL($columns[$column_name])) {
						$QwriteData=self::getConnection();
						$QwriteData->prepare('ALTER TABLE `:table:` CHANGE :column_name: :column_data: :position:');
						$QwriteData->bindTable(':table:', 'c_'.$table_details['details']['table_name']);
						$QwriteData->bindRaw(':column_name:', $column_name);
						$QwriteData->bindRaw(':column_data:', $this->createColumnString($column));
						if ($column_name_last=='') {
							$QwriteData->bindRaw(':position:', 'FIRST');
						} else {
							$QwriteData->bindRaw(':position:', 'AFTER `'.$column_name_last.'`');
						}
						$QwriteData->execute();
					}
					if (isset($columns[$column_name])) {
						unset($columns[$column_name]);
					}
					$column_name_last=$column_name;
				}
				foreach ($columns as $column_name=>$column_details) {
					$QwriteData=self::getConnection();
					$QwriteData->prepare('ALTER TABLE `:table:` DROP `:column_name:`');
					$QwriteData->bindTable(':table:', 'c_'.$table_details['details']['table_name']);
					$QwriteData->bindRaw(':column_name:', $column_name);
					$QwriteData->execute();
				}
			}
		}

		return true;
	}

	/**
	 * @param array $column_details
	 * @return string
	 */
	public function createColumnString(array $column_details):string {
		$column=[];
		$column[]='`'.$column_details['column_name'].'`';
		$column[]=$column_details['column_type'].'('.$column_details['column_length'].')';
		if ($column_details['column_collation']!=='') {
			if (in_array($column_details['column_type'], ['int', 'tinyint', 'double'])) {
				$column[]=$column_details['column_collation'];
			}
			if (in_array($column_details['column_type'], ['varchar', 'text'])) {
				$column[]='COLLATE \''.$column_details['column_collation'].'\'';
			}
		}
		if ($column_details['column_setnull']==1) {
			$column[]='NULL';
		} else {
			$column[]='NOT NULL';
		}
		if ($column_details['column_autoincrement']==1) {
			$column[]='AUTO_INCREMENT';
		} else {
			if (in_array($column_details['column_type'], ['int', 'tinyint', 'double'])) {
				$column[]='DEFAULT 0';
			}
			if (in_array($column_details['column_type'], ['varchar', 'text'])) {
				$column[]='DEFAULT \'\'';
			}
		}

		return implode(' ', $column);
	}

	/**
	 * @param array $column_details
	 * @return string
	 */
	public function createColumnStringFromMYSQL(array $column_details):string {
		$column=[];
		$column[]='`'.$column_details['Field'].'`';
		$column[]=$column_details['Type'];
		if ($column_details['Collation']!==null) {
			$column[]='COLLATE \''.$column_details['Collation'].'\'';
		}
		if ($column_details['Null']=='NO') {
			$column[]='NOT NULL';
		} else {
			$column[]='NULL';
		}
		if ($column_details['Default']===null) {
			if ($column_details['Extra']=='auto_increment') {
				$column[]='AUTO_INCREMENT';
			}
		} else {
			if ($column_details['Default']=='0') {
				$column[]='DEFAULT 0';
			}

			if ($column_details['Default']=='') {
				$column[]='DEFAULT \'\'';
			}
		}

		return implode(' ', $column);
	}

}

?>