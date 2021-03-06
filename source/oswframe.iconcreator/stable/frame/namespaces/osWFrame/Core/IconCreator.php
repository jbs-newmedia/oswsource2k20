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

$file=Settings::getStringVar('settings_abspath').'frame'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'php-ico'.DIRECTORY_SEPARATOR.'class-php-ico.php';
if ((file_exists($file))&&(class_exists('PHP_ICO')!==true)) {
	require_once $file;
}

class IconCreator extends \PHP_ICO {

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
	 * IconCreator constructor.
	 *
	 * @param string $file
	 * @param array $sizes
	 */
	public function __construct(string $file='', array $sizes=[]) {
		if ($file=='') {
			parent::__construct(false, $sizes);
		} else {
			parent::__construct($file, $sizes);
		}
	}

	/**
	 * @param $file
	 * @return bool
	 */
	public function saveIcon($file):bool {
		return $this->save_ico($file);
	}

	/**
	 * @param $file
	 * @param $sizes
	 * @return bool
	 */
	public static function existsCache($file, $sizes) {
		$filenamecache=md5($file.'#'.serialize($sizes));
		if ((Cache::existsCache(self::getClassName(), $filenamecache)!==true)||((Filesystem::getFileModTime($file)>Cache::getCacheModTime(self::getClassName(), $filenamecache)))) {
			return false;
		}

		return true;
	}

	/**
	 * @param $file
	 * @param $sizes
	 * @return string
	 */
	public static function readCache($file, $sizes):string {
		$filenamecache=md5($file.'#'.serialize($sizes));

		return Cache::readCacheAsString(self::getClassName(), $filenamecache);
	}

	/**
	 * @param $file
	 * @param $sizes
	 * @return bool
	 */
	public function writeCache($file, $sizes):bool {
		$filenamecache=md5($file.'#'.serialize($sizes));
		if (!$this->_has_requirements) {
			return false;
		}

		if (false===($data=$this->_get_ico_data())) {
			return false;
		}

		return Cache::writeCache($this->getClassName(), $filenamecache, $data);
	}

}

?>