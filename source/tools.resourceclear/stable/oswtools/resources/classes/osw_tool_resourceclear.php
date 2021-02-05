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
class osW_Tool_ResourceClear extends osW_Tool_Object {

	public $data=[];

	function __construct() {
	}

	function __destruct() {
	}

	// http://www.php.net/manual/de/function.readdir.php#100710
	function listdir($dir='.') {
		if (!is_dir($dir)) {
			return [];
		}

		if (substr($dir, -1, 1)=='/') {
			$dir=substr($dir, 0, -1);
		}

		$files=[];
		$this->listdiraux($dir, $files);

		return $files;
	}

	// http://www.php.net/manual/de/function.readdir.php#100710
	function listdiraux($dir, &$files) {
		$handle=opendir($dir);
		while (($file=readdir($handle))!==false) {
			if ($file=='.'||$file=='..') {
				continue;
			}

			$filepath=$dir=='.'?$file:$dir.'/'.$file;
			if (is_dir($filepath)) {
				if (file_exists($filepath.'.resource')) {
					$files[]=str_replace(root_path.osW_Tool::getInstance()->getFrameConfig('resource_path', 'string'), '', $filepath);
				} else {
					$this->listdiraux($filepath, $files);
				}
			}
		}
		closedir($handle);
	}

	/**
	 *
	 * @return osW_Tool_ResourceClear
	 */
	public static function getInstance() {
		return parent::getInstance();
	}

}

?>