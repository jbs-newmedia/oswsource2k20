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

class Scripts {

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
	 * @var string
	 */
	private string $lock_dir='';

	/**
	 * @var string
	 */
	private string $lock_file='';

	/**
	 * @var string
	 */
	private string $lock_file_global='';

	/**
	 * @var int
	 */
	private int $lock_timeout=0;

	/**
	 * Scripts constructor.
	 *
	 * @param string $script_name
	 */
	public function __construct(string $script_name='') {
		$this->setLockDir();
		if ($script_name!='') {
			$this->setLockFile($script_name);
		}
		$this->setGlobalLockFile();
		$this->setLockTimeOut(Settings::getIntVar('scripts_lock_timeout'));
	}

	/**
	 * @return bool
	 */
	public function setLockDir():bool {
		$this->lock_dir=Settings::getStringVar('settings_abspath').Settings::getStringVar('scripts_lock_path');
		if (Filesystem::isDir($this->getLockDir())!==true) {
			Filesystem::makeDir($this->getLockDir());
			Filesystem::protectDir($this->getLockDir());
		}

		return true;
	}

	/**
	 * @return string
	 */
	public function getLockDir():string {
		return $this->lock_dir;
	}

	/**
	 * @param string $script_name
	 * @return bool
	 */
	public function setLockFile(string $script_name) {
		$this->lock_file=$this->getLockDir().str_replace(['.'], ['_'], strtolower(basename($script_name))).'.slock';

		return true;
	}

	/**
	 * @return string
	 */
	public function getLockFile():string {
		return $this->lock_file;
	}

	/**
	 * @param string $script_name
	 * @return bool
	 */
	public function setGlobalLockFile() {
		$this->lock_file_global=$this->getLockDir().'global.slock';

		return true;
	}

	/**
	 * @return string
	 */
	public function getGlobalLockFile():string {
		return $this->lock_file_global;
	}

	/**
	 * @param int $lock_timeout
	 * @return true
	 */
	public function setLockTimeOut(int $lock_timeout):bool {
		$this->lock_timeout=$lock_timeout;

		return true;
	}

	/**
	 * @return int
	 */
	public function getLockTimeOut():int {
		return $this->lock_timeout;
	}

	/**
	 * @return bool
	 */
	public function setLock():bool {
		file_put_contents($this->getLockFile(), time());

		return true;
	}

	/**
	 * @return bool
	 */
	public function checkLock():bool {
		if (Filesystem::existsFile($this->getLockFile())!==true) {
			$this->setLock();

			return true;
		}

		if (intval(file_get_contents($this->getLockFile()))<=(time()-$this->getLockTimeOut())) {
			$this->setLock();

			return true;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function clearLock():bool {
		if (Filesystem::existsFile($this->getLockFile())===true) {
			unlink($this->getLockFile());

			return true;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function setGlobalLock():bool {
		file_put_contents($this->getGlobalLockFile(), time());

		return true;
	}

	/**
	 * @return bool
	 */
	public function checkGlobalLock():bool {
		if (Filesystem::existsFile($this->getGlobalLockFile())!==true) {
			return true;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function clearGlobalLock():bool {
		if (Filesystem::existsFile($this->getGlobalLockFile())===true) {
			unlink($this->getGlobalLockFile());

			return true;
		}

		return false;
	}

}

?>