<?php

/**
 *
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package oswFrame - Tools
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 *
 */
class osW_Tool_ProjectClear extends osW_Tool_Object {

	public $data=array();

	function __construct() {
	}

	function __destruct() {
	}

	public function getList($ignore) {
		$list=array();

		$_ignore=array();

		$_ignore['files']=array('/frame/configure.php', '/modules/configure.project.php', '/modules/configure.project-dev.php', '/oswtools/.htaccess', '/oswtools/.htpasswd');
		if (isset($ignore['files'])) {
			foreach ($ignore['files'] as $file) {
				if ($file!='') {
					$_ignore['files'][]=$file;
				}
			}
		}

		$_ignore['dirs']=array('/oswtools/resources/json/configure', '/oswtools/resources/caches', '/oswtools/resources/settings', '/oswtools/resources/session');
		if (isset($ignore['dirs'])) {
			foreach ($ignore['dirs'] as $dir) {
				if ($dir!='') {
					$_ignore['dirs'][]=$dir;
				}
			}
		}

		$_list=$this->listdir(substr(root_path, 0, -1));
		asort($_list);

		foreach ($_list as $element) {
			$break=false;

			if (((substr($element, 0, 2)=='/.')||(substr($element, 0, 5)=='/data'))&&($break===false)) {
				$break=true;
			}

			if ((in_array($element, $_ignore['files']))&&($break===false)) {
				$break=true;
			}

			foreach ($_ignore['dirs'] as $dir) {
				if (strstr($element, $dir)) {
					$break=true;
					break;
				}
			}

			if ($break===false) {
				$node=root_path.substr($element, 1);
				if (!is_dir($node)) {
					$list[$element]=sha1_file($node);
				} else {
					$list[$element]=sha1_file($node);
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

		$ar_list=array();
		foreach ($list as $element => $checksum) {
			if ($checksum!='') {
				if (isset($_list[$element])) {
					$ar_list[substr($element, 1)]=1;
					unset($list[$element]);
					unset($_list[$element]);
				} else {
					$ar_list[substr($element, 1)]=2;
				}
			}
		}

		foreach ($_list as $element => $checksum) {
			$ar_list[substr($element, 1)]=3;
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

		return $files;
	}

	// http://www.php.net/manual/de/function.readdir.php#100710
	public function listdiraux($dir, &$files) {
		$handle=opendir($dir);

		while (($file=readdir($handle))!==false) {
			if ($file=='.'||$file=='..') {
				continue;
			}

			$filepath=$dir.'/'.$file;
			$files[]=str_replace(root_path, '/', $filepath);
			if (is_dir($filepath)) {
				$this->listdiraux($filepath, $files);
			}
		}
		closedir($handle);
	}

	// http://www.php.net/manual/de/function.readdir.php#100710
	function listdirSettings($dir='.') {
		if (!is_dir($dir)) {
			return array();
		}

		$files=array();
		$this->listdirauxSettings($dir, $files, 1);

		return $files;
	}

	// http://www.php.net/manual/de/function.readdir.php#100710
	function listdirauxSettings($dir, &$files, $i) {
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
					$this->listdirauxSettings($filepath, $files, $i);
				}
			}
		}
		$i--;
		closedir($handle);
	}

	/**
	 *
	 * @return osW_Tool_ProjectClear
	 */
	public static function getInstance() {
		return parent::getInstance();
	}
}

?>