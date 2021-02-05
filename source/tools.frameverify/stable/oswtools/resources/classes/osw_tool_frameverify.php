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
class osW_Tool_FrameVerify extends osW_Tool_Object {

	public $data=array();

	function __construct() {
	}

	function __destruct() {
	}

	public function getList($dir='.') {
		$list=array();

		$_list=$this->listdir($dir);
		asort($_list);

		foreach ($_list as $element) {
			if ((substr($element, 0, 2)=='/.')||(substr($element, 0, 5)=='/data')) {
			} elseif (in_array($element, array('/frame/configure.php', '/modules/configure.project.php', '/modules/configure.project-dev.php'))) {
			} elseif ((strstr($element, '/oswtools/resources/json/'))||(strstr($element, '/oswtools/resources/caches/'))) {
			} else {
				$node=root_path.$element;
				if (!is_dir($node)) {
					$list[$element]=sha1_file($node);
				} else {
					$list[$element]='';
				}
			}
		}

		$path=root_path.'oswtools/resources/json/filelist/';

		$_list=array();
		$file_lists=scandir($path);
		foreach ($file_lists as $file_list) {
			if (substr($file_list, -5, 5)=='.json') {
				$file_list=json_decode(file_get_contents($path.$file_list), true);
				if ((count($file_list)>0)&&(!isset($file_list[0]))) {
					$_list=$_list+$file_list;
				}
			}
		}

		foreach ($_list as $element => $checksum) {
			if ((substr($element, 0, 2)=='/.')||(substr($element, 0, 5)=='/data')) {
				unset($list[$element]);
				unset($_list[$element]);
			} elseif (in_array($element, array('/modules/configure.project.php', '/modules/configure.project-dev.php'))) {
				unset($list[$element]);
				unset($_list[$element]);
			} elseif ((strstr($element, '/oswtools/resources/json/'))||(strstr($element, '/oswtools/resources/caches/'))) {
				unset($list[$element]);
				unset($_list[$element]);
			} else {
				if ((isset($list[$element]))&&($list[$element]==$checksum)) {
					unset($list[$element]);
					unset($_list[$element]);
				}
			}
		}

		$ar_list=array();
		foreach ($list as $element => $checksum) {
			if (isset($_list[$element])) {
				$ar_list[substr($element, 1)]=1;
				unset($list[$element]);
				unset($_list[$element]);
			} else {
				$ar_list[substr($element, 1)]=2;
			}
		}

		foreach ($_list as $element => $checksum) {
#			$ar_list[substr($element, 1)]=3;
		}

		ksort($ar_list);
		return $ar_list;
	}

	// http://www.php.net/manual/de/function.readdir.php#100710
	public function listdir($dir='.') {
		if (!is_dir($dir)) {
			return array();
		}

		$files=array();
		$this->listdiraux($dir, $files);

		if (isset($files[''])) {
			unset($files['']);
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
			$files[]=str_replace(root_path, '/', $filepath);
			if (is_dir($filepath)) {
				$this->listdiraux($filepath, $files);
			}
		}
		closedir($handle);
	}

	/**
	 *
	 * @return osW_Tool_FrameVerify
	 */
	public static function getInstance() {
		return parent::getInstance();
	}
}

?>