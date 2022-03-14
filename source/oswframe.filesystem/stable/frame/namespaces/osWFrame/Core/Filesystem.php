<?php

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license MIT License
 */

namespace osWFrame\Core;

class Filesystem {

	use BaseStaticTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=1;

	/**
	 * Minor-Version der Klasse.
	 */
	private const CLASS_MINOR_VERSION=4;

	/**
	 * Release-Version der Klasse.
	 */
	private const CLASS_RELEASE_VERSION=1;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	/**
	 * Filesystem constructor.
	 */
	private function __construct() {

	}

	/**
	 * Löscht eine Datei.
	 *
	 * @param string $file
	 * @return bool
	 */
	public static function unlink(string $file):bool {
		if (!file_exists($file)) {
			return true;
		}
		if (!is_file($file)) {
			return true;
		}
		if (@unlink($file)===true) {
			return true;
		}

		return false;
	}

	/**
	 * Prüft ob es sich um ein Verzeichnis handelt.
	 *
	 * @param string $filename
	 * @return bool
	 */
	public static function isDir(string $dirname):bool {
		self::clearStatcache();

		return is_dir($dirname);
	}

	/**
	 * @param string $dirname
	 * @return bool
	 */
	public static function isEmptyDir(string $dirname):bool {
		if (count(self::scanDir($dirname))==2) {
			return true;
		}

		return false;
	}

	/**
	 * Gibt den Verzeichnisnamen zurück.
	 *
	 * @param string $filename
	 * @return string
	 */
	public static function getDirName(string $filename):string {
		if (substr($filename, -1, 1)==DIRECTORY_SEPARATOR) {
			$filename.='.';
		}

		return dirname($filename).DIRECTORY_SEPARATOR;
	}

	/**
	 * Erstellt ein Verzeichnis.
	 *
	 * @param string $filename
	 * @param int $mod
	 * @return bool
	 */
	public static function makeDir(string $filename, int $mod=0):bool {
		$filename=self::getDirName($filename);
		if (self::isDir($filename)===true) {
			return true;
		}
		if ($mod==0) {
			$mod=Settings::getIntVar('settings_chmod_dir');
		}
		if (mkdir($filename, $mod, true)!==true) {
			return false;
		}
		self::changeFilemodeFromBase($filename, $mod);
		self::clearStatcache();

		return true;
	}

	/**
	 * @param string $dirname
	 * @return bool
	 */
	public static function protectDir(string $dirname):bool {
		if (substr($dirname, -1)!==DIRECTORY_SEPARATOR) {
			$dirname.=DIRECTORY_SEPARATOR;
		}
		self::makeDir($dirname);
		$file=$dirname.'.htaccess';
		if (self::existsFile($file)!==true) {
			file_put_contents($file, "order deny,allow\ndeny from all");
			self::changeFilemode($file);
		}

		return true;
	}

	/**
	 * Ändert die Zugriffsrechte der Datei.
	 *
	 * @param string $filename
	 * @param int $mod
	 * @return bool
	 */
	public static function changeFilemode(string $filename, int $mod=0):bool {
		if ($mod==0) {
			$mod=Settings::getIntVar('settings_chmod_file');
		}

		if (self::existsFile($filename)!==true) {
			return false;
		}

		return chmod($filename, $mod);
	}

	/**
	 * Ändert die Zugriffsrechte des Verzeichnisses.
	 *
	 * @param string $dirname
	 * @param int $mod
	 * @return bool
	 */
	public static function changeDirmode(string $dirname, int $mod=0):bool {
		if ($mod==0) {
			$mod=Settings::getIntVar('settings_chmod_dir');
		}

		if (self::isDir($dirname)!==true) {
			return false;
		}

		return chmod($dirname, $mod);
	}

	/**
	 * Ändert die Zugriffsrechte der Datei rekursiv.
	 *
	 * @param string $filename
	 * @param int $mod
	 * @return bool
	 */
	public static function changeFilemodeFromBase(string $filename, int $mod=0):bool {
		if ($mod==0) {
			$mod=Settings::getIntVar('settings_chmod_dir');
		}
		$list=explode(DIRECTORY_SEPARATOR, str_replace(Settings::getStringVar('settings_abspath'), '', $filename));
		$dir=Settings::getStringVar('settings_abspath');
		foreach ($list as $_dir) {
			if ($_dir!='') {
				$dir.=$_dir.DIRECTORY_SEPARATOR;
				self::changeFileMode($dir, $mod);
			}
		}

		return true;
	}

	/**
	 * Prüft ob es sich um eine Datei handelt.
	 *
	 * @param string $filename
	 * @return bool
	 */
	public static function isFile(string $filename):bool {
		self::clearStatcache();

		return is_file($filename);
	}

	/**
	 * Prüft ob die Datei existiert.
	 *
	 * @param string $filename
	 * @return bool
	 */
	public static function existsFile(string $filename):bool {
		self::clearStatcache();

		return file_exists($filename);
	}

	/**
	 * Scannt ein Verzeichnis.
	 *
	 * @param string $dirname
	 * @return array|null
	 */
	public static function scanDir(string $dirname):?array {
		$dirname=self::getDirName($dirname);

		if (self::isDir($dirname)) {
			return scandir($dirname);
		}

		return null;
	}

	/**
	 * Scannt ein Verzeichnis rekursiv und liefert die Liste als Array.
	 *
	 * @param string $dir
	 * @param bool $recursive
	 * @param int $deep
	 * @param bool $only_deep_result
	 * @return array|null
	 */
	public static function scanDirToArray(string $dir, bool $recursive=false, int $deep=0, bool $only_deep_result=false):?array {
		return self::scanDirToArrayCore($dir, $recursive, $deep, 'fd', 0, [], $only_deep_result);
	}

	/**
	 * Scannt ein Verzeichnis rekursiv und liefert die Liste der Verzeichnisse als Array.
	 *
	 * @param string $dir
	 * @param bool $recursive
	 * @param int $deep
	 * @param bool $only_deep_result
	 * @return array|null
	 */
	public static function scanDirsToArray(string $dir, bool $recursive=false, int $deep=0, bool $only_deep_result=false):?array {
		return self::scanDirToArrayCore($dir, $recursive, $deep, 'd', 0, [], $only_deep_result);
	}

	/**
	 * Scannt ein Verzeichnis rekursiv und liefert die Liste der Dateien als Array.
	 *
	 * @param string $dir
	 * @param bool $recursive
	 * @param int $deep
	 * @param bool $only_deep_result
	 * @return array|null
	 */
	public static function scanFilesToArray(string $dir, bool $recursive=false, int $deep=0, bool $only_deep_result=false):?array {
		return self::scanDirToArrayCore($dir, $recursive, $deep, 'f', 0, [], $only_deep_result);
	}

	/**
	 * Kürzt einen Array mit Verzeichnissen/Dateien um den angegebenen Pfad.
	 *
	 * @param string $dir
	 * @param array $list
	 * @return array
	 */
	public static function trimPathInArray(string $dir, array $list):array {
		$len=mb_strlen($dir);
		foreach ($list as $key=>$value) {
			$list[$key]=mb_substr($value, $len, -1);
		}

		return $list;
	}

	/**
	 * Engine zum Scannen von Verzeichnissen.
	 *
	 * @param string $dir
	 * @param bool $recursive
	 * @param int $deep
	 * @param string $mode
	 * @param int $current_level
	 * @param array $result
	 * @return array|null
	 */
	private static function scanDirToArrayCore(string $dir, bool $recursive=false, int $deep=0, string $mode='fd', int $current_level=0, array $result=[], bool $only_deep_result=false):?array {
		$dir=self::getDirName($dir);
		if (self::isDir($dir)!==true) {
			return null;
		}
		$list=self::scanDir($dir);
		$current_level++;
		if (!empty($list)) {
			foreach ($list as $f) {
				if (($f!='..')&&($f!='.')) {
					if (self::isDir($dir.$f)) {
						if (mb_strpos($mode, 'd')!==false) {
							if (($only_deep_result==false)||(($only_deep_result===true)&&($current_level==$deep))) {
								$result[]=$dir.$f.DIRECTORY_SEPARATOR;
							}
						}
						if (($recursive===true)&&(($deep==0)||($deep>$current_level))) {
							$result=self::scanDirToArrayCore($dir.$f.DIRECTORY_SEPARATOR, $recursive, $deep, $mode, $current_level, $result);
						}
					} else {
						if (mb_strpos($mode, 'f')!==false) {
							if (($only_deep_result==false)||(($only_deep_result===true)&&($current_level==$deep))) {
								$result[]=$dir.$f;
							}
						}
					}
				}
			}
		}

		return $result;
	}

	/**
	 * Kopiert eine Datei.
	 *
	 * @param string $source
	 * @param string $dest
	 * @return bool
	 */
	public static function copyFile(string $source, string $dest):bool {
		return copy($source, $dest);
	}

	/**
	 * Löscht den Status Cache.
	 */
	public static function clearStatcache():void {
		clearstatcache();
	}

	/**
	 * Gibt den letzen Aktualisierungszeitpunkt alle Dateien der Liste zurück.
	 *
	 * @param array $files
	 * @param bool $check_configs
	 * @return int
	 */
	public static function getFilesModTime(array $files, bool $check_configs=false):int {
		$filesmtime=0;
		if ($check_configs===true) {
			foreach (Settings::getConfigFiles() as $file) {
				$filesmtime=max(filemtime($file), $filesmtime);
			}
		}
		foreach ($files as $file) {
			if (file_exists($file)) {
				$filesmtime=max(filemtime($file), $filesmtime);
			}
		}

		return $filesmtime;
	}

	/**
	 * Gibt den Aktualisierungszeitpunkt einer Datei zurück.
	 *
	 * @param string $file
	 * @param bool $check_configs
	 * @return int
	 */
	public static function getFileModTime(string $file, bool $check_configs=false):int {
		return self::getFilesModTime([$file], $check_configs);
	}

	/**
	 * @param string $file
	 * @param int|null $mtime
	 * @param int|null $atime
	 * @return bool
	 */
	public static function setFileModTime(string $file, ?int $mtime=null, ?int $atime=null):bool {
		return touch($file, $mtime, $atime);
	}

	/**
	 * Löscht eine Datei. Alias für unlink().
	 *
	 * @param string $file
	 * @return bool
	 */
	public static function delFile(string $file):bool {
		return self::unlink($file);
	}

	/**
	 * @param string $oldname
	 * @param string $newname
	 * @return bool
	 */
	public static function renameFile(string $oldname, string $newname):bool {
		return rename($oldname, $newname);
	}

	/**
	 * Löscht ein Verzeichnis.
	 *
	 * @param string $dir
	 * @return bool
	 */
	public static function delDir(string $dir):bool {
		$files=array_diff(self::scanDir($dir), ['.', '..']);
		foreach ($files as $file) {
			if (self::isDir($dir.$file)) {
				self::delDir($dir.$file.DIRECTORY_SEPARATOR);
			} else {
				self::delFile($dir.$file);
			}
		}

		return rmdir($dir);
	}

}

?>