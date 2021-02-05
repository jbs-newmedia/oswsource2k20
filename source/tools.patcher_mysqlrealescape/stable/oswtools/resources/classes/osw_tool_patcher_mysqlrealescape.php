<?php

/**
 *
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package oswFrame - Tools
 * @link http://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 *
 */
class osW_Tool_Patcher_MySQLRealEscape extends osW_Tool_Object {

	public $data=array();

	function __construct() {
	}

	function __destruct() {
	}

	public function patchFile($filename) {
		$data=file_get_contents($filename);
		$data=str_replace('mysql_escape_string(', 'osW_Database::getInstance()->escape_string(', $data);
		$data=str_replace('mysql_real_escape_string(', 'osW_Database::getInstance()->escape_string(', $data);
		file_put_contents($filename, $data);
	}

	public function getList() {
		$_list=$this->listdir(substr(root_path, 0, -1));
		return $_list;
	}

	// http://www.php.net/manual/de/function.readdir.php#100710
	public function listdir($dir='.') {
		if (!is_dir($dir)) {
			return array();
		}

		$files=array();
		$this->listdiraux($dir, $files);

		if (isset($files['/oswtools/resources/classes/osw_tool_patcher_mysqlrealescape.php'])) {
			unset($files['/oswtools/resources/classes/osw_tool_patcher_mysqlrealescape.php']);
		}

		return $files;
	}

	// http://www.php.net/manual/de/function.readdir.php#100710
	public function listdiraux($dir, &$files) {
		$handle=opendir($dir);
		while (($file=readdir($handle))!==false) {
			if ($file=='.'||$file=='..') {
				continue;
			}

			$filepath=$dir=='.'?$file:$dir.'/'.$file;
			if (is_dir($filepath)) {
				$this->listdiraux($filepath, $files);
			} else {
				if (substr(strtolower($filepath), -4)=='.php') {
					$content=file_get_contents($filepath);
					if (strstr($content, 'mysql_escape_string(')) {
						$files[str_replace(root_path, '/', $filepath)]=str_replace(root_path, '/', $filepath);
					}
					if (strstr($content, 'mysql_real_escape_string(')) {
						$files[str_replace(root_path, '/', $filepath)]=str_replace(root_path, '/', $filepath);
					}
				}
			}
		}
		closedir($handle);
	}

	/**
	 *
	 * @return osW_Tool_Patcher_MySQLRealEscape
	 */
	public static function getInstance() {
		return parent::getInstance();
	}
}

?>