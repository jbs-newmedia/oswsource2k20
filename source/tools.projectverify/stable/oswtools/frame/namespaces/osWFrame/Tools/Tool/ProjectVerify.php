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

namespace osWFrame\Tools\Tool;

use osWFrame\Core as Frame;
use osWFrame\Tools as Tools;

class ProjectVerify extends CoreTool {

	use Frame\BaseStaticTrait;
	use Tools\BaseSettingsTrait;

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
	private const CLASS_RELEASE_VERSION=4;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	/**
	 * @var array
	 */
	protected array $list=[];

	/**
	 * @var array
	 */
	protected array $scanlist=[];

	/**
	 * @var array
	 */
	protected array $readlist=[];

	/**
	 * ProjectVerify constructor.
	 *
	 * @param string $serverlist
	 * @param string $package
	 * @param string $release
	 */
	public function __construct(string $serverlist, string $package, string $release) {
		parent::__construct($serverlist, $package, $release);
		$this->loadSettings();
	}

	/**
	 * @return $this
	 */
	protected function setIgnoreDefaultList():self {
		$this->settings['projectverify_dirs'][]='data'.DIRECTORY_SEPARATOR;
		$this->settings['projectverify_dirs'][]='oswtools'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'configure'.DIRECTORY_SEPARATOR;
		$this->settings['projectverify_dirs'][]='oswtools'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'filelist'.DIRECTORY_SEPARATOR;
		$this->settings['projectverify_dirs'][]='oswtools'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'packagelist'.DIRECTORY_SEPARATOR;
		$this->settings['projectverify_dirs'][]='oswtools'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'serverlist'.DIRECTORY_SEPARATOR;
		$this->settings['projectverify_dirs'][]='oswtools'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'settings'.DIRECTORY_SEPARATOR;
		$this->settings['projectverify_dirs'][]='oswtools'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'sources'.DIRECTORY_SEPARATOR;
		$this->settings['projectverify_dirs'][]='oswvendor'.DIRECTORY_SEPARATOR;
		$this->settings['projectverify_files'][]='frame'.DIRECTORY_SEPARATOR.'configure.php';
		$this->settings['projectverify_files'][]='modules'.DIRECTORY_SEPARATOR.'configure.project.php';
		$this->settings['projectverify_files'][]='oswtools'.DIRECTORY_SEPARATOR.'frame.key';

		return $this;
	}

	/**
	 * @return $this
	 */
	protected function createList():self {
		$this->setIgnoreDefaultList();
		$this->readList();

		$path=\osWFrame\Core\Settings::getStringVar('settings_framepath').'oswtools'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'filelist'.DIRECTORY_SEPARATOR;
		foreach (glob($path.'*.json') as $file_list) {
			$file_list=json_decode(file_get_contents($file_list), true);
			if ((count($file_list)>0)&&(!isset($file_list[0]))) {
				foreach ($file_list as $_file=>$checksum) {
					if (substr(basename($_file), 0, 1)!='.') {
						if ($checksum=='') {
							$go=true;
							foreach ($this->settings['projectverify_dirs'] as $idir) {
								if ((DIRECTORY_SEPARATOR.$idir==substr($_file, 0, strlen(DIRECTORY_SEPARATOR.$idir)))||(DIRECTORY_SEPARATOR.$idir==substr($_file.DIRECTORY_SEPARATOR, 0, strlen($idir)+1))) {
									$go=false;
								}
							}
							if ($go===true) {
								$this->readlist[substr($_file, 1)]=$checksum;
							}
						} else {
							$go=true;
							foreach ($this->settings['projectverify_dirs'] as $idir) {
								if (DIRECTORY_SEPARATOR.$idir==substr($_file, 0, strlen(DIRECTORY_SEPARATOR.$idir))) {
									$go=false;
								}
							}
							foreach ($this->settings['projectverify_files'] as $ifile) {
								if (DIRECTORY_SEPARATOR.$ifile==$_file) {
									$go=false;
								}
							}
							if ($go===true) {
								$this->readlist[substr($_file, 1)]=$checksum;
							}
						}
					}
				}
			}
		}
		ksort($this->readlist);

		foreach ($this->scanlist as $element=>$checksum) {
			if (isset($this->readlist[$element])) {
				if ($this->readlist[$element]!=$checksum) {
					if ($checksum=='') {
						$this->list[$element]=['t'=>'d', 's'=>1];
					} else {
						$this->list[$element]=['t'=>'f', 's'=>1];
					}
				}
				unset($this->scanlist[$element]);
				unset($this->readlist[$element]);
			} else {
				if ($checksum=='') {
					$this->list[$element]=['t'=>'d', 's'=>2];
				} else {
					$this->list[$element]=['t'=>'f', 's'=>2];
				}
			}
		}

		foreach ($this->readlist as $element=>$checksum) {
			if ($checksum=='') {
				$this->list[$element]=['t'=>'d', 's'=>3];
			} else {
				$this->list[$element]=['t'=>'f', 's'=>3];
			}
		}

		ksort($this->list);

		return $this;
	}

	/**
	 * @return $this
	 */
	public function readList():self {
		$this->setIgnoreDefaultList();
		$dir=\osWFrame\Core\Settings::getStringVar('settings_framepath');
		$this->scanlist=[];
		if (Frame\Filesystem::isDir($dir)) {
			$this->scanDirToArray($dir);
			ksort($this->scanlist);
		}

		return $this;
	}

	/**
	 * Engine zum Scannen von Verzeichnissen.
	 *
	 * @param string $dir
	 * @return $this
	 */
	protected function scanDirToArray(string $dir):self {
		$list=Frame\Filesystem::scanDir($dir);
		if (!empty($list)) {
			foreach ($list as $f) {
				if (substr($f, 0, 1)!='.') {
					if (Frame\Filesystem::isDir($dir.$f)) {
						$_dir=str_replace(\osWFrame\Core\Settings::getStringVar('settings_framepath'), '', $dir.$f);
						$go=true;
						foreach ($this->settings['projectverify_dirs'] as $idir) {
							if (($idir==$_dir)||($idir==$_dir.DIRECTORY_SEPARATOR)) {
								$go=false;
							}
						}
						if ($go===true) {
							$this->scanlist[$_dir]='';
							$this->scanDirToArray($dir.$f.DIRECTORY_SEPARATOR);
						}
					} else {
						$_file=str_replace(\osWFrame\Core\Settings::getStringVar('settings_framepath'), '', $dir.$f);
						$go=true;
						foreach ($this->settings['projectverify_files'] as $ifile) {
							if ($ifile==$_file) {
								$go=false;
							}
						}
						if ($go===true) {
							$this->scanlist[$_file]=sha1_file($dir.$f);
						}
					}
				}
			}
		}

		return $this;
	}

	/**
	 * @param array $_projectverify_files
	 * @param array $_projectverify_dirs
	 * @param bool $load_settings
	 * @return bool
	 */
	public function updateSettings(array $_projectverify_files, array $_projectverify_dirs, bool $load_settings=false):bool {
		if (($load_settings===true)&&($this->getArraySetting('projectverify_files')!==null)) {
			$projectverify_files=$this->getArraySetting('projectverify_files');
		} else {
			$projectverify_files=[];
		}
		foreach ($_projectverify_files as $value) {
			$value=trim($value);
			if (($value!='')&&(!in_array($value, $projectverify_files))) {
				$projectverify_files[]=$value;
			}
		}
		if (($load_settings===true)&&($this->getArraySetting('projectverify_dirs')!==null)) {
			$projectverify_dirs=$this->getArraySetting('projectverify_dirs');
		} else {
			$projectverify_dirs=[];
		}
		foreach ($_projectverify_dirs as $value) {
			$value=trim($value);
			if (($value!='')&&(!in_array($value, $projectverify_dirs))) {
				$projectverify_dirs[]=$value;
			}
		}

		sort($projectverify_files);
		$this->setArraySetting('projectverify_files', $projectverify_files);
		sort($projectverify_dirs);
		$this->setArraySetting('projectverify_dirs', $projectverify_dirs);
		$this->writeSettings();

		return true;
	}

	/**
	 * @param string $element
	 * @param string $type
	 * @return bool
	 */
	public function addIgnore(string $element, string $type):bool {
		if ($type=='f') {
			return $this->updateSettings([$element], [], true);
		}
		if ($type=='d') {
			return $this->updateSettings([], [$element.DIRECTORY_SEPARATOR], true);
		}

		return false;
	}

	/**
	 * @return array
	 */
	public function getList():array {
		$this->createList();

		return $this->list;
	}

	/**
	 *
	 */
	public function forceDownload():void {
		$serverlist=[];
		$path=\osWFrame\Core\Settings::getStringVar('settings_framepath').'oswtools'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'packagelist'.DIRECTORY_SEPARATOR;
		foreach (glob($path.'*.json') as $package_list) {
			$file_list=json_decode(file_get_contents($package_list), true);
			foreach ($file_list as $package=>$element) {
				$serverlist[$package]=substr(str_replace($path, '', $package_list), 0, -5);
			}
		}

		$filelist=[];
		$path=\osWFrame\Core\Settings::getStringVar('settings_framepath').'oswtools'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'filelist'.DIRECTORY_SEPARATOR;
		foreach (glob($path.'*.json') as $file_list) {
			$list=$file_list;
			$file_list=json_decode(file_get_contents($file_list), true);
			$filelist[$list]=$file_list;
		}

		$packagelist=[];
		foreach ($filelist as $list=>$elements) {
			foreach ($elements as $file=>$checksum) {
				if ($checksum!='') {
					$packagelist[substr($file, 1)]=substr(str_replace($path, '', $list), 0, -5);
				}
			}
		}

		$file='projectverify-'.date('YmdHis').'.zip';
		$zfile=Frame\Settings::getStringVar('settings_abspath').Frame\Settings::getStringVar('cache_path').DIRECTORY_SEPARATOR.$file;
		$Zip=new Frame\Zip($zfile);
		if ($Zip->openFile(\ZIPARCHIVE::CREATE)===true) {
			$del_list=[];
			foreach ($this->getList() as $element=>$status) {
				if ((isset($packagelist[$element]))&&(isset($serverlist[$packagelist[$element]]))) {
					// change
					if ($status['s']==1) {
						$Zip->addEmptyDir($serverlist[$packagelist[$element]].DIRECTORY_SEPARATOR.'source'.DIRECTORY_SEPARATOR.str_replace('-', DIRECTORY_SEPARATOR, $packagelist[$element]).DIRECTORY_SEPARATOR.dirname($element));
						if ($status['t']=='f') {
							$Zip->addFile(Frame\Settings::getStringVar('settings_framepath').$element, $serverlist[$packagelist[$element]].DIRECTORY_SEPARATOR.'source'.DIRECTORY_SEPARATOR.str_replace('-', DIRECTORY_SEPARATOR, $packagelist[$element]).DIRECTORY_SEPARATOR.$element);
						}
					}
					if ($status['s']==3) {
						$del_list[$serverlist[$packagelist[$element]]][]='source'.DIRECTORY_SEPARATOR.str_replace('-', DIRECTORY_SEPARATOR, $packagelist[$element]).DIRECTORY_SEPARATOR.$element;
					}
				} else {
					if ($status['s']==2) {
						if ($status['t']=='d') {
							$Zip->addEmptyDir('new_files'.DIRECTORY_SEPARATOR.$element.DIRECTORY_SEPARATOR);
						}
						if ($status['t']=='f') {
							if (dirname($element)!='.') {
								$Zip->addEmptyDir('new_files'.DIRECTORY_SEPARATOR.dirname($element).DIRECTORY_SEPARATOR);
							}
							$Zip->addFile(Frame\Settings::getStringVar('settings_framepath').$element, 'new_files'.DIRECTORY_SEPARATOR.$element);
						}
					}
				}
			}
			foreach ($del_list as $list=>$files) {
				if ($list!='') {
					$Zip->addEmptyDir($list.DIRECTORY_SEPARATOR.'source'.DIRECTORY_SEPARATOR);
					$Zip->addFromString($list.DIRECTORY_SEPARATOR.'source'.DIRECTORY_SEPARATOR.'deleted_files.list', implode("\n", $files));
				}
			}
			$Zip->close();
		}
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"".$file."\"");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".filesize($zfile));
		readfile($zfile);
		Frame\Filesystem::delFile($zfile);
		Frame\Settings::dieScript();
	}

}

?>