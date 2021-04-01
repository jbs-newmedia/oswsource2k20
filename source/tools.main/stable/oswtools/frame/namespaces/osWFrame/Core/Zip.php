<?php

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

namespace osWFrame\Core;

class Zip {

	use BaseStaticTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=1;

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
	private const CLASS_EXTRA_VERSION='';

	/**
	 * @var object|ZipArchive|null
	 */
	private ?object $Zip=null;

	/**
	 * @var string
	 */
	private string $file='';

	/**
	 * Zip constructor.
	 *
	 * @param string $file
	 */
	public function __construct(string $file) {
		$this->file=$file;
		$this->Zip=new \ZipArchive();
	}

	/**
	 * @return string
	 */
	public function getFile():string {
		return $this->file;
	}

	/**
	 * @param string $dir
	 * @param string $file
	 * @return bool
	 */
	public function packDir(string $dir):bool {
		if ($this->Zip->open($this->getFile(), \ZipArchive::CREATE)===true) {
			$this->packDirEngine($dir);
			$this->Zip->close();
			return true;
		}
		return false;
	}

	/**
	 * @param string $dir
	 * @return bool
	 */
	public function packDirEngine(string $dir):bool {
		$handle=opendir($dir);
		while ($fp=readdir($handle)) {
			if (($fp!='.')&&($fp!='..')) {
				$file=$dir.$fp;
				if (is_dir($file)) {
					$this->Zip->addEmptyDir(str_replace($dir, '', $file));
					$this->packDirEngine($file.DIRECTORY_SEPARATOR);
				}
				if (is_file($file)) {
					$this->Zip->addFile($file, str_replace($dir, '', $file));
				}
			}
		}
		closedir($handle);
		return true;
	}

	/**
	 * @param $dir
	 * @param int $chmod_dir
	 * @param int $chmod_file
	 * @return bool
	 */
	public function unpackDir(string $dir, int $chmod_dir=0755, int $chmod_file=0644):bool {
		$this->Zip->open($this->getFile());
		if ($this->Zip->numFiles>0) {
			if (!is_dir($dir)) {
				mkdir($dir);
			}
			@chmod($dir, $chmod_dir);
			for ($i=0; $i<$this->Zip->numFiles; $i++) {
				$stat=$this->Zip->statIndex($i);
				if (($stat['crc']==0)&&($stat['size']==0)) {
					if (!is_dir($dir.$stat['name'])) {
						mkdir($dir.$stat['name']);
					}
					@chmod($dir.$stat['name'], $chmod_dir);
				} else {
					$data=$this->Zip->getFromIndex($i);
					file_put_contents($dir.$stat['name'], $data);
					@chmod($dir.$stat['name'], $chmod_file);
				}
			}
			return true;
		}
		return false;
	}

}

?>