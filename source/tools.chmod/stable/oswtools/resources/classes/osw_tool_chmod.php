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
class osW_Tool_CHmod extends osW_Tool_Object {

	public $data=array();

	function __construct() {
	}

	function __destruct() {
	}

	// http://www.php.net/manual/de/function.readdir.php#100710
	function listdir($dir='.') {
		if (!is_dir($dir)) {
			return array();
		}

		$files=array();
		$this->listdiraux($dir, $files, 1);

		return $files;
	}

	// http://www.php.net/manual/de/function.readdir.php#100710
	function listdiraux($dir, &$files, $i) {
		$i++;
		$handle=opendir($dir);
		while (($file=readdir($handle))!==false) {
			if ($file=='.'||$file=='..') {
				continue;
			}

			$filepath=$dir=='.'?$file:$dir.'/'.$file;
			if (is_dir($filepath)) {
				if ($i<5) {
					$files[]=str_replace(root_path, '/', $filepath).'/';
					$this->listdiraux($filepath, $files, $i);
				}
			}
		}
		$i--;
		closedir($handle);
	}

	/**
	 *
	 * @return osW_Tool_CHmod
	 */
	public static function getInstance() {
		return parent::getInstance();
	}
}

?>