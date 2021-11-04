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

namespace osWFrame\Tools\Tool;

use osWFrame\Core as Frame;
use osWFrame\Tools as Tools;

class ChangeMod extends CoreTool {

	use Frame\BaseStaticTrait;

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
	private array $dir_list=[];

	/**
	 * @var array
	 */
	private array $chmod_file=[];

	/**
	 * @var array
	 */
	private array $chmod_dir=[];

	/**
	 * ChangeMod constructor.
	 *
	 * @param string $serverlist
	 * @param string $package
	 * @param string $release
	 */
	public function __construct(string $serverlist, string $package, string $release) {
		parent::__construct($serverlist, $package, $release);
		$this->chmod_file=['0664'=>'0664', '0644'=>'0644', '0660'=>'0660', '0640'=>'0640'];
		$this->chmod_dir=['0775'=>'0775', '0755'=>'0755', '0770'=>'0770', '0750'=>'0750'];
	}

	/**
	 * @param string $dir
	 * @return array
	 */
	public function readDirList(string $dir):object {
		$this->dir_list=[];
		if (Frame\Filesystem::isDir($dir)) {
			$dirs=Frame\Filesystem::scanDirsToArray($dir, true, 2);

			foreach ($dirs as $key=>$value) {
				$this->dir_list[md5($key)]=str_replace($dir, '', $value);
			}

			return $this;
		}
	}

	/**
	 * @return array
	 */
	public function getDirList():array {
		return $this->dir_list;
	}

	/**
	 * @return array
	 */
	public function getFileOptions():array {
		return $this->chmod_file;
	}

	/**
	 * @return string
	 */
	public function getFile():string {
		$chmod_file=Tools\Configure::getFrameConfigInt('settings_chmod_file');
		switch ($chmod_file) {
			case 436:
				$chmod_file='0664';
				break;
			case 420:
				$chmod_file='0644';
				break;
			case 432:
				$chmod_file='0660';
				break;
			case 416:
				$chmod_file='0640';
				break;
			default:
				$chmod_file='0664';
				break;
		}

		if (!in_array($chmod_file, $this->getFileOptions())) {
			$chmod_file='0664';
		}

		return $chmod_file;
	}

	/**
	 * @return array
	 */
	public function getDirOptions():array {
		return $this->chmod_dir;
	}

	/**
	 * @return string
	 */
	public function getDir():string {
		$chmod_dir=Tools\Configure::getFrameConfigInt('settings_chmod_file');
		switch ($chmod_dir) {
			case 509:
				$chmod_dir='0775';
				break;
			case 493:
				$chmod_dir='0755';
				break;
			case 504:
				$chmod_dir='0770';
				break;
			case 488:
				$chmod_dir='0750';
				break;
			default:
				$chmod_dir='0775';
				break;
		}

		if (!in_array($chmod_dir, $this->getDirOptions())) {
			$chmod_dir='0777';
		}

		return $chmod_dir;
	}

}

?>