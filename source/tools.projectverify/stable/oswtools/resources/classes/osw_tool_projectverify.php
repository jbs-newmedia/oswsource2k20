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
class osW_Tool_ProjectVerify extends osW_Tool_Object {

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

		$_ignore['dirs']=array('/oswtools/resources/json/', '/oswtools/resources/caches/', '/oswtools/resources/settings/', '/oswtools/resources/session/');
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

			if ($_ignore['dirs']!='') {
				foreach ($_ignore['dirs'] as $dir) {
					if ($dir!='') {
						if (strstr($element, $dir)) {
							$break=true;
							break;
						}
					}
				}
			}

			if ($break===false) {
				$node=root_path.substr($element, 1);
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
			$break=false;
			if (((substr($element, 0, 2)=='/.')||(substr($element, 0, 5)=='/data'))&&($break===false)) {
				$break=true;
			}

			if ((in_array($element, $_ignore['files']))&&($break===false)) {
				$break=true;
			}

			if ($_ignore['dirs']!='') {
				foreach ($_ignore['dirs'] as $dir) {
					if ($dir!='') {
						if (strstr($element, $dir)) {
							$break=true;
							break;
						}
					}
				}
			}

			if ((isset($list[$element]))&&($list[$element]==$checksum)) {
				$break=true;
			}

			if ($break==true) {
				unset($list[$element]);
				unset($_list[$element]);
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

			$filepath=$dir.'/'.$file;
			$files[]=str_replace(root_path, '/', $filepath);
			if (is_dir($filepath)) {
				$this->listdiraux($filepath, $files);
			}
		}
		closedir($handle);
	}

	public function createUpdatePackageZIP($ar_list) {
		if ($ar_list==array()) {
			return false;
		}
		$serverlist=array();

		$path=root_path.'oswtools/resources/json/packagelist/';
		$file_lists=scandir($path);
		foreach ($file_lists as $package_list) {
			if (substr($package_list, -5, 5)=='.json') {
				$file_list=json_decode(file_get_contents($path.$package_list), true);
				foreach ($file_list as $package=>$element) {
					$serverlist[$package]=substr($package_list, 0, -5);
				}
			}
		}
		$path=root_path.'oswtools/resources/json/filelist/';
		$_list=array();
		$file_lists=scandir($path);
		foreach ($file_lists as $file_list) {
			$list=$file_list;
			if (substr($file_list, -5, 5)=='.json') {
				$file_list=json_decode(file_get_contents($path.$file_list), true);
				$_list[$list]=$file_list;
			}
		}

		$filepackage=array();
		foreach ($_list as $list => $elements) {
			foreach ($elements as $file => $checksum) {
				if ($checksum!='') {
					$filepackage[substr($file, 1)]=substr($list, 0, -5);
				}
			}
		}

		$path=abs_path.'resources/caches/';
		$file='projectverify-'.date('YmdHis').'.zip';
		$zip=new ZipArchive;
		if ($zip->open($path.$file, ZIPARCHIVE::CREATE)===true) {
			$del_list=array();
			foreach ($ar_list as $element => $status) {
				if ((isset($filepackage[$element]))&&(isset($serverlist[$filepackage[$element]]))) {
					// change
					if ($status==1) {
						$zip->addEmptyDir($serverlist[$filepackage[$element]].'/source/'.str_replace('-', '/', $filepackage[$element]).'/'.dirname($element));
						$zip->addFile(root_path.$element, $serverlist[$filepackage[$element]].'/source/'.str_replace('-', '/', $filepackage[$element]).'/'.$element);
					}
					if ($status==3) {
						$del_list[$serverlist[$filepackage[$element]]][]='source/'.str_replace('-', '/', $filepackage[$element]).'/'.$element;
					}
				} else {
					if ($status==2) {
						if (dirname($element)!='.') {
							$zip->addEmptyDir('new_files/'.dirname($element).'/');
						}
						$zip->addFile(root_path.$element, 'new_files/'.$element);
					}
				}
			}

			foreach ($del_list as $list => $files) {
				if ($list!='') {
					$zip->addEmptyDir($list.'/source/');
					$zip->addFromString($list.'/source/deleted_files.list', implode("\n", $files));
				}
			}
			$zip->close();
		}
		ob_clean();
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"".$file."\"");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".filesize($path.$file));
		readfile($path.$file);
		die();
	}

	/**
	 *
	 * @return osW_Tool_ProjectVerify
	 */
	public static function getInstance() {
		return parent::getInstance();
	}
}

?>