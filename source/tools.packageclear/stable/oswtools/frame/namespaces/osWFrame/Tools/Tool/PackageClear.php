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

class PackageClear extends CoreTool {

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
	private const CLASS_RELEASE_VERSION=3;

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
	 * ProjectClear constructor.
	 *
	 * @param string $serverlist
	 * @param string $package
	 * @param string $release
	 */
	public function __construct(string $serverlist, string $package, string $release) {
		parent::__construct($serverlist, $package, $release);
	}

	/**
	 * @return $this
	 */
	private function createList():self {
		foreach (['changelog', 'configure', 'filelist', 'package'] as $key) {
			$path=\osWFrame\Core\Settings::getStringVar('settings_framepath').'oswtools'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.$key.DIRECTORY_SEPARATOR;
			foreach (glob($path.'*.json') as $file_list) {
				if (!isset($this->readlist[basename($file_list, '.json')])) {
					$this->readlist[basename($file_list, '.json')]=['changelog'=>false, 'configure'=>false, 'filelist'=>false, 'package'=>false];
				}
				$this->readlist[basename($file_list, '.json')][$key]=true;
			}
		}

		$path=\osWFrame\Core\Settings::getStringVar('settings_framepath').'oswtools'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'serverlist'.DIRECTORY_SEPARATOR;
		foreach (glob($path.'*.json') as $file_list) {
			$file=\osWFrame\Core\Settings::getStringVar('settings_framepath').'oswtools'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'packagelist'.DIRECTORY_SEPARATOR.basename($file_list);
			$list=json_decode(file_get_contents($file), true);
			foreach ($list as $key=>$value) {
				$key=trim($key);
				if (isset($this->readlist[$key])) {
					unset($this->readlist[$key]);
				}
			}
		}
		ksort($this->readlist);

		return $this;
	}

	/**
	 * @return array
	 */
	public function getList():array {
		$this->createList();

		return $this->readlist;
	}

	/**
	 * @return $this
	 */
	public function clearPackages():self {
		foreach ($this->getList() as $name=>$status) {
			if ((isset($_POST['package'][$name]))&&($_POST['package'][$name]=='1')) {
				foreach (['changelog', 'configure', 'filelist', 'package'] as $key) {
					if ($status[$key]===true) {
						$file=\osWFrame\Core\Settings::getStringVar('settings_framepath').'oswtools'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.$key.DIRECTORY_SEPARATOR.$name.'.json';;
						if (Frame\Filesystem::existsFile($file)) {
							Frame\Filesystem::delFile($file);
						}
					}
				}
			}
		}

		return $this;
	}

}

?>