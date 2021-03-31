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

class Main extends CoreTool {

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
	private array $htusers=[];

	/**
	 * @var object|Tools\Manager|null
	 */
	private ?object $Manager=null;

	/**
	 * CacheClear constructor.
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
	public function getTools():array {
		if ($this->tools==[]) {
			$tools=self::getLocalTools();
			$this->Manager->setKeys(['tool'])->getServerPackageList()->checkPackageList();
			foreach ($this->Manager->getPackageList() as $current_serverlist=>$server_packages) {
				$this->tools[$current_serverlist]=[];
				foreach ($server_packages as $package_name=>$package_data) {
					$package=$package_data['package'].'.'.$package_data['release'];
					if (isset($tools[$package])) {
						if (isset($package_data['info']['name'])) {
							$this->tools[$current_serverlist][$package]=$package_data;
							unset($tools[$package]);
						}
					}
				}
			}

			if ($tools!=[]) {
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

		return $this->tools;
	}

	/**
	 * @return array
	 */
	public function getHTUsers():array {
		$htpasswd_file=Frame\Settings::getStringVar('settings_abspath').'.htpasswd';

		$this->htusers=[];
		if (file_exists($htpasswd_file)) {
			$htpasswd=file($htpasswd_file);
			if (count($htpasswd)>0) {
				foreach ($htpasswd as $user) {
					if (strlen($user)>3) {
						$ar_user=explode(':', $user);
						if (count($ar_user)>=2) {
							$this->htusers[$ar_user[0]]=trim($user);
						}
					}
				}
			}
		} else {
			$this->htusers=[];
		}

		return $this->htusers;
	}

}

?>