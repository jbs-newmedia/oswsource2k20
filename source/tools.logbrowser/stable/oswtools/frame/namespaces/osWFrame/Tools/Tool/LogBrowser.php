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

class LogBrowser extends CoreTool {

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
	private const CLASS_EXTRA_VERSION='beta';

	/**
	 * @var array
	 */
	private array $dir_list=[];

	/**
	 * @var array
	 */
	private array $file_list=[];

	/**
	 * @var array
	 */
	private array $file_list_unsort=[];

	/**
	 * @var array
	 */
	private array $file_details=[];

	/**
	 * LogBrowser constructor.
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
	 * @return object
	 */
	public function readLogDirs(string $dir):object {
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
	public function getLogDirs():array {
		return $this->dir_list;
	}

	/**
	 * @param string $dir
	 * @return bool
	 */
	public function isDir(string $dir):bool {
		if (in_array($dir, $this->dir_list)===true) {
			return true;
		}

		return false;
	}

	/**
	 * @param string $file
	 * @return bool
	 */
	public function isFile(string $file):bool {
		if (in_array($file, $this->file_list_unsort)===true) {
			return true;
		}

		return false;
	}

	/**
	 * @param string $dir
	 * @return object
	 */
	public function readLogFiles(string $dir):object {
		$this->file_list=[];
		if (Frame\Filesystem::isDir($dir)) {
			$lastday=date('Ymd', time()-(60*60*24*intval(\osWFrame\Tools\Configure::getFrameConfigInt('debug_maxdays'))));
			$dirs=Frame\Filesystem::scanFilesToArray($dir, true, 1, true);
			foreach ($dirs as $value) {
				if (checkdate(substr(basename($value), 4, 2), substr(basename($value), 6, 2), substr(basename($value), 0, 4))===true) {
					$lday=intval(substr(basename($value), 0, 8));
					if ($lday>=$lastday) {
						$this->file_list_unsort[]=str_replace($dir, '', $value);
						$this->file_list[$lday][]=str_replace($dir, '', $value);
					}
				} else {
					$this->file_list_unsort[]=str_replace($dir, '', $value);
					$this->file_list[0][]=str_replace($dir, '', $value);
				}

			}
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function getLogFiles():array {
		return $this->file_list;
	}

	/**
	 * @param string $dir
	 * @param string $file
	 * @param string $display
	 * @return object
	 */
	public function loadFile(string $dir, string $file, string $display):object {
		if (Frame\Filesystem::existsFile($dir.$file)===true) {
			if (substr($file, -3)=='csv') {
				$this->file_details['type']='csv';
				$lines=file($dir.$file);
				if (count($lines)>0) {
					$this->file_details['head']=explode('";"', substr(trim($lines[0]), 1, -1));
					unset($lines[0]);
					$lines=array_reverse($lines);
					$this->file_details['lines']=[];
					foreach ($lines as $id=>$line) {
						$lines_content=explode('";"', substr(trim($line), 1, -1));
						foreach ($lines_content as $key=>$value) {
							if ($value=='') {
								$value='-';
							}
							if ($this->file_details['head'][$key]=='time') {
								$this->file_details['lines'][$id][$key]=date('Y.m.d H:i:s', intval($value));
							} else {
								$this->file_details['lines'][$id][$key]=str_replace('#oswbr#', "\n", $value);
							}
						}
					}
					if ($display=='analysis') {
						$this->analyseLines();
					}
				} else {
					$this->file_details['lines']=[];
				}
			} else {
				$this->file_details['type']='txt';
				$this->file_details['content']=nl2br(file_get_contents($dir.$file));
			}
		} else {
			$this->file_details['type']='err';
			$this->file_details['content']='&nbsp;';
		}

		return $this;
	}

	public function analyseLines():object {
		$unset=[];
		foreach ($this->file_details['head'] as $key=>$value) {
			if ($value=='time') {
				$unset[]=$key;
				unset($this->file_details['head'][$key]);
			}
		}
		$this->file_details['head'][-1]='count';
		ksort($this->file_details['head']);

		$lines=[];
		foreach ($this->file_details['lines'] as $line) {
			if ($unset!==[]) {
				foreach ($unset as $_key) {
					unset($line[$_key]);
				}
			}
			$md5=md5(serialize($line));
			if (!isset($lines[$md5])) {
				$lines[$md5]=$line;
				$lines[$md5][-1]=0;
				ksort($lines[$md5]);
			}
			$lines[$md5][-1]++;
		}
		uasort($lines, [$this, 'compareList']);
		$this->file_details['lines']=$lines;

		return $this;
	}

	/**
	 * @param array $a
	 * @param array $b
	 * @return int
	 */
	public function compareList(array $a, array $b):int {
		return $a[-1]<$b[-1];
	}

	/**
	 * @return string
	 */
	public function getFileDetailType():string {
		if (isset($this->file_details['type'])) {
			return $this->file_details['type'];
		}

		return '';
	}

	/**
	 * @return array
	 */
	public function getFileDetailHead():array {
		if (isset($this->file_details['head'])) {
			return $this->file_details['head'];
		}

		return [];
	}

	/**
	 * @return array
	 */
	public function getFileDetailLines():array {
		if (isset($this->file_details['lines'])) {
			return $this->file_details['lines'];
		}

		return [];
	}

	/**
	 * @return string
	 */
	public function getFileDetailContent():string {
		if (isset($this->file_details['content'])) {
			return $this->file_details['content'];
		}

		return '';
	}

	/**
	 * @return string[]
	 */
	public function getDisplayOptions():array {
		if ($this->getFileDetailType()=='csv') {
			return ['table'=>'Show table layout', 'analysis'=>'Show analysis layout'];
		} else {
			return ['file'=>'Show file layout'];
		}
	}

	/**
	 * @param string $type
	 * @param string $curdisplay
	 * @return string
	 */
	public static function getCurrentDisplayOption(string $type, string $curdisplay):string {
		if ($type=='csv') {
			switch ($curdisplay) {
				case 'analysis':
					return 'Show analysis layout';
					break;
				case 'table':
				default:
					return 'Show table layout';
					break;

			}
		} else {
			return 'Show file layout';
		}
	}

}

?>