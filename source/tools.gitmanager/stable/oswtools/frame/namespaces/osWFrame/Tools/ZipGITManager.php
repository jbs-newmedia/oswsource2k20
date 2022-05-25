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

namespace osWFrame\Tools;

use osWFrame\Core\BaseStaticTrait;
use osWFrame\Core\Filesystem;
use osWFrame\Core\Settings;
use osWFrame\Core\Zip;

class ZipGITManager extends Zip {

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
	 * @var array
	 */
	protected array $packages=[];

	/**
	 * ZipGITManager constructor.
	 *
	 * @param string $file
	 */
	public function __construct(string $file) {
		parent::__construct($file);
	}

	/**
	 * @param string $dir
	 * @param string $path
	 * @return bool
	 */
	public function unpackGitDir(string $dir, string $path=''):bool {
		$gitpath='';
		$chmod_dir=Settings::getIntVar('settings_chmod_dir');
		$chmod_file=Settings::getIntVar('settings_chmod_file');
		$this->openFile();
		if ($this->count()>0) {
			if (Filesystem::isDir($dir)!==true) {
				Filesystem::makeDir($dir, $chmod_dir);
			}
			Filesystem::changeDirmode($dir, $chmod_dir);
			for ($i=0; $i<$this->count(); $i++) {
				$stat=$this->statIndex($i);
				if (($gitpath=='')||(strpos($stat['name'], $gitpath)===0)) {
					if (($stat['crc']==0)&&($stat['size']==0)) {
						if ($i==0) {
							$gitpath=$stat['name'];
							if ($path!='') {
								$gitpath.=$path;
							}
						}
						if (Filesystem::isDir($dir.$stat['name'])!==true) {
							Filesystem::makeDir($dir.$stat['name'], $chmod_dir);
						}
						Filesystem::changeDirmode($dir.$stat['name'], $chmod_dir);
					} else {
						$data=$this->getFromIndex($i);
						file_put_contents($dir.$stat['name'], $data);
						Filesystem::changeFilemode($dir.$stat['name'], $chmod_file);
					}
				}
			}

			return true;
		}

		return false;
	}

}

?>