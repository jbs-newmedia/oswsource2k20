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
class osW_Tool_FrameClear extends osW_Tool_Object {

	public $data=array();

	function __construct() {
	}

	function __destruct() {
	}

	public function getList($dir='.') {
		$_list=$this->listdir($dir);
		asort($_list);

		$list=array();
		foreach ($_list as $element) {
			$list[$element]=substr($element, 1);
		}

		$path=root_path.'oswtools/resources/json/filelist/';

		$file_lists=scandir($path);
		foreach ($file_lists as $file_list) {
			if (substr($file_list, -5, 5)=='.json') {
				$file_list=json_decode(file_get_contents($path.$file_list), true);
				if (count($file_list)>0) {
					foreach ($file_list as $element => $foo) {
						if (isset($list[$element])) {
							unset($list[$element]);
						}
					}
				}
			}
		}

		$file='/frame/configure.php';
		if (isset($list[$file])) {
			unset($list[$file]);
		}

		return $list;
	}

	// http://www.php.net/manual/de/function.readdir.php#100710
	public function listdir($dir='.') {
		if (!is_dir($dir)) {
			return array();
		}

		$files=array();
		$this->listdiraux($dir, $files);

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
			$files[]=str_replace(root_path, '/', $filepath);
			if (is_dir($filepath)) {
				$this->listdiraux($filepath, $files);
			}
		}
		closedir($handle);
	}

	/**
	 *
	 * @return osW_Tool_FrameClear
	 */
	public static function getInstance() {
		return parent::getInstance();
	}
}

?>