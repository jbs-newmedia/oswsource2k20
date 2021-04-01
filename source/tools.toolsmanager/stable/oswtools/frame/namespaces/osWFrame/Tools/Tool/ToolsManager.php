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

class ToolsManager extends CoreTool {

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
	private array $tools_local=[];

	/**
	 * @var array
	 */
	private array $tools=[];

	/**
	 * @var array
	 */
	private array $server_list=[];

	/**
	 * @var array
	 */
	private array $htusers=[];

	/**
	 * @var string
	 */
	private string $sl='';

	/**
	 * @var object|Tools\Manager|null
	 */
	private ?object $Manager=null;

	/**
	 * ToolsManager constructor.
	 *
	 * @param string $serverlist
	 * @param string $package
	 * @param string $release
	 */
	public function __construct(string $serverlist, string $package, string $release) {
		parent::__construct($serverlist, $package, $release);
		$this->Manager=new Tools\Manager();
	}

	/**
	 * @return object
	 */
	public function scanLocalTools():object {
		if ($this->tools_local===[]) {
			$path=Frame\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR;
			foreach (scandir($path) as $node) {
				if ((substr($node, 0, 6)=='tools.')&&($node!='tools.main.stable')) {
					$this->tools_local[$node]=$node;
				}
			}
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function getLocalTools():array {
		$this->scanLocalTools();

		return $this->tools_local;
	}

	/**
	 * @return array
	 */
	public function loadTools():object {
		if ($this->tools==[]) {
			$tools=self::getLocalTools();
			$this->Manager->setKeys(['tool'])->getServerPackageList()->checkPackageList();
			foreach ($this->Manager->getPackageList() as $current_serverlist=>$server_packages) {
				$this->tools[$current_serverlist]=[];
				foreach ($server_packages as $package_name=>$package_data) {
					$package=$package_data['package'].'.'.$package_data['release'];
					$this->tools[$current_serverlist][$package]=$package_data;
					if (isset($tools[$package])) {
						unset($tools[$package]);
					}
				}
			}

			$this->server_list=[''=>['info'=>['name'=>'*']]]+Tools\Server::getServerList();

			if ($tools!=[]) {
				$this->server_list['custom']['info']['name']='Custom';
				foreach ($tools as $package) {
					$file=Frame\Settings::getStringVar('settings_abspath').$package.DIRECTORY_SEPARATOR.'info.json';
					if (file_exists($file)) {
						$this->tools['custom'][$package]=json_decode(file_get_contents($file), true);
					} else {
						$this->tools['custom'][$package]=$package;
					}
				}
			}
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function getTools():array {
		return $this->tools;
	}

	/**
	 * @return array
	 */
	public function getList():array {
		return $this->server_list;
	}

	/**
	 * @param string $sl
	 * @return object
	 */
	public function setSL(string $sl):object {
		if (!isset($this->server_list[$sl])) {
			$sl='';
		}
		$this->sl=$sl;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getSL():string {
		return $this->sl;
	}

	/**
	 * @param string $link
	 * @param string $i
	 * @param array $package_data
	 * @param string $sl
	 * @return string
	 */
	public function outputOption(string $link, string $i, array $package_data, string $sl):string {
		$output='';
		if ($package_data['options']['install']==true) {
			$output.='<a title="Install" href="javascript:manager(\''.$i.'\', \''.$link.'\', \'install\', \''.$sl.'\', \''.$package_data['package'].'\', \''.$package_data['release'].'\')" class="install btn btn-primary btn-xs"><i class="fas fa-plus fa-fw"></i></a>';
		} else {
			$output.='<a title="Install" href="javascript:manager(\''.$i.'\', \''.$link.'\', \'install\', \''.$sl.'\', \''.$package_data['package'].'\', \''.$package_data['release'].'\')" class="install btn btn-primary btn-xs disabled"><i class="fas fa-plus fa-fw"></i></a>';
		}
		$output.=' ';
		if ($package_data['options']['update']==true) {
			$output.='<a title="Update" href="javascript:manager(\''.$i.'\', \''.$link.'\', \'update\', \''.$sl.'\', \''.$package_data['package'].'\', \''.$package_data['release'].'\')" class="update btn btn-primary btn-xs"><i class="fa fa-sync fa-fw"></i></a>';
		} else {
			$output.='<a title="Update" href="javascript:manager(\''.$i.'\', \''.$link.'\', \'update\', \''.$sl.'\', \''.$package_data['package'].'\', \''.$package_data['release'].'\')" class="update btn btn-primary btn-xs disabled"><i class="fa fa-sync fa-fw"></i></a>';
		}
		$output.=' ';
		if ($package_data['options']['remove']==true) {
			$output.='<a title="Remove" href="javascript:manager(\''.$i.'\', \''.$link.'\', \'remove\', \''.$sl.'\', \''.$package_data['package'].'\', \''.$package_data['release'].'\')" class="remove btn btn-primary btn-xs"><i class="fa fa-times fa-fw"></i></a>';
		} else {
			$output.='<a title="Remove" href="javascript:manager(\''.$i.'\', \''.$link.'\', \'remove\', \''.$sl.'\', \''.$package_data['package'].'\', \''.$package_data['release'].'\')" class="remove btn btn-primary btn-xs disabled"><i class="fa fa-times fa-fw"></i></a>';
		}

		return $output;
	}

	/**
	 * @param string $serverlist
	 * @param string $package
	 * @param string $release
	 * @return ?array
	 */
	public function getPackageDetails(string $serverlist, string $package, string $release):?array {
		return $this->Manager->getPackageDetails($serverlist, $package, $release);
	}

	/**
	 * @param string $serverlist
	 * @param string $package
	 * @param string $release
	 * @return bool
	 */
	public function installPackage(string $serverlist, string $package, string $release):bool {
		return $this->Manager->installPackage($serverlist, $package, $release);
	}

	/**
	 * @param string $serverlist
	 * @param string $package
	 * @param string $release
	 * @return bool
	 */
	public function removePackage(string $serverlist, string $package, string $release):bool {
		$status=$this->Manager->removePackage($serverlist, $package, $release);
		$this->Manager->checkPackageList();

		return $status;
	}

}

?>