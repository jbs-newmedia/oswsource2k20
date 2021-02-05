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
class osW_Tool_Zip extends osW_Tool_Object {

	private $data=array();

	function __construct() {
	}

	function __destruct() {
	}

	public function packDir($dir, $file) {
		$name=md5($dir.'###'.$file);
		$this->data[$name]['dir']=$dir;
		$this->data[$name]['zip']=new ZipArchive();
		if ($this->data[$name]['zip']->open($file, ZipArchive::CREATE)===true) {
			$this->packDirEngine($name, $this->data[$name]['dir']);
			$this->data[$name]['zip']->close();
			return true;
		}
		return false;
	}

	public function packDirEngine($name, $dir) {
		$handle=opendir($dir);
		while ($datei=readdir($handle)) {
			if (($datei!='.')&&($datei!='..')) {
				$file=$dir.$datei;
				if (is_dir($file)) {
					$this->data[$name]['zip']->addEmptyDir(str_replace($this->data[$name]['dir'], '', $file));
					$this->packDirEngine($name, $file.'/');
				}
				if (is_file($file)) {
					$this->data[$name]['zip']->addFile($file, str_replace($this->data[$name]['dir'], '', $file));
				}
			}
		}
		closedir($handle);
		return true;
	}

	public function unpackDir($file, $dir, $chmod_dir=0755, $chmod_file=0644) {
		$name=md5($file.'###'.$dir);
		$this->data[$name]['zip']=new ZipArchive();
		$this->data[$name]['zip']->open($file);
		if ($this->data[$name]['zip']->numFiles>0) {
			if (!is_dir($dir)) {
				mkdir($dir);
			}
			@chmod($dir, $chmod_dir);
			for ($i=0; $i<$this->data[$name]['zip']->numFiles; $i++) {
				$stat=$this->data[$name]['zip']->statIndex($i);
				if (($stat['crc']==0)&&($stat['size']==0)) {
					# dir
					if (!is_dir($dir.$stat['name'])) {
						mkdir($dir.$stat['name']);
					}
					@chmod($dir.$stat['name'], $chmod_dir);
				} else {
					#file
					$data=$this->data[$name]['zip']->getFromIndex($i);
					file_put_contents($dir.$stat['name'], $data);
					@chmod($dir.$stat['name'], $chmod_file);
				}
			}
			return true;
		}
		return false;
	}

	/**
	 *
	 * @return osW_Tool_Zip
	 */
	public static function getInstance() {
		return parent::getInstance();
	}
}

?>