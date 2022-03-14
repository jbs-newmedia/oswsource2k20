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

class Lock {

	use BaseStaticTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=1;

	/**
	 * Minor-Version der Klasse.
	 */
	private const CLASS_MINOR_VERSION=1;

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
	 * @var array
	 */
	private array $locks=[];

	/**
	 * Lock constructor.
	 */
	public function __construct() {
	}

	/**
	 * @param string $module
	 * @param string $lock
	 * @return true
	 */
	public function lock(string $module, string $lock):bool {
		$module=strtolower($module);

		$dir=Settings::getStringVar('settings_abspath').Settings::getStringVar('lock_path');

		if (Filesystem::isDir($dir)!==true) {
			Filesystem::makeDir($dir);
			Filesystem::protectDir($dir);
		}

		$dir=$dir.$module.DIRECTORY_SEPARATOR;

		if (Filesystem::isDir($dir)!==true) {
			Filesystem::makeDir($dir);
		}

		$this->locks[$module.'_'.$lock]=fopen($dir.$lock.'.lock', "w+");
		if (flock($this->locks[$module.'_'.$lock], LOCK_EX|LOCK_NB)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param string $module
	 * @param string $lock
	 * @return bool
	 */
	public function unlock(string $module, string $lock):bool {
		$module=strtolower($module);
		$result=flock($this->locks[$module.'_'.$lock], LOCK_UN);
		fclose($this->locks[$module.'_'.$lock]);

		$module=strtolower($module);
		$filename=Settings::getStringVar('settings_abspath').Settings::getStringVar('lock_path').$module.DIRECTORY_SEPARATOR.$lock.'.lock';
		Filesystem::unlink($filename);

		return $result;
	}

	/**
	 * @param string $module
	 * @param string $lock
	 * @param int $milliseconds
	 * @param int $retry
	 * @return bool
	 */
	public function wlock(string $module, string $lock, int $milliseconds=1000, int $retry=10):bool {
		$milliseconds=$milliseconds*1000;

		while ($retry>0) {
			$retry--;
			if ($this->lock($module, $lock)===true) {
				return true;
			} else {
				usleep($milliseconds);
			}
		}

		return false;
	}

}

?>