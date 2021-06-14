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

class LogClear extends CoreTool {

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
	 * LogClear constructor.
	 *
	 * @param string $serverlist
	 * @param string $package
	 * @param string $release
	 */
	public function __construct(string $serverlist, string $package, string $release) {
		parent::__construct($serverlist, $package, $release);
	}

	/**
	 * @param string $dir
	 * @return array
	 */
	public function readLogList(string $dir):object {
		$this->dir_list=[];

		if (Frame\Filesystem::isDir($dir)) {
			$dirs=Frame\Filesystem::scanDirsToArray($dir, true, 1, true);

			foreach ($dirs as $key=>$value) {
				$this->dir_list[$key]=str_replace($dir, '', $value);
			}
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function getLogList():array {
		return $this->dir_list;
	}

}

?>