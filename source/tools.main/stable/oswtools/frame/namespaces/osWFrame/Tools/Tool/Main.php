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

class Main extends CoreTool {

	use Frame\BaseStaticTrait;

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
		$this->checkHTAccess();
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
	public function loadHTUsers():object {
		$htpasswd_file=Frame\Settings::getStringVar('settings_abspath').'.htpasswd';

		$this->htusers=[];
		if (file_exists($htpasswd_file)) {
			$htpasswd=file($htpasswd_file);
			if (count($htpasswd)>0) {
				foreach ($htpasswd as $user) {
					if (strlen($user)>3) {
						$ar_user=explode(':', $user);
						if (count($ar_user)>=2) {
							$this->htusers[$ar_user[0]]=trim(str_replace($ar_user[0].':', '', $user));
						}
					}
				}
			}
		} else {
			$this->htusers=[];
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function getHTUsers():array {
		if ($this->htusers==[]) {
			$this->loadHTUsers();
		}

		return $this->htusers;
	}

	/**
	 * @param array $users
	 * @return object
	 */
	public function removeHTUsers(array $users):object {
		if ($this->htusers==[]) {
			$this->loadHTUsers();
		}

		foreach ($users as $user) {
			if (isset($this->htusers[$user])) {
				unset($this->htusers[$user]);
			}
		}
		$this->writeHTAccess();
		$this->loadHTUsers();

		return $this;
	}

	/**
	 * @param string $username
	 * @param string $password
	 * @return object
	 */
	public function addHTUser(string $username, string $password):object {
		if ($this->htusers==[]) {
			$this->loadHTUsers();
		}
		$this->htusers[$username]=crypt($password, crypt('pass12$hz', 'oswtools'));

		return $this;
	}

	/**
	 * @return object
	 */
	public function writeHTAccess():object {
		$file_ht=\osWFrame\Core\Settings::getStringVar('settings_abspath').'.htaccess';
		$file_pw=\osWFrame\Core\Settings::getStringVar('settings_abspath').'.htpasswd';
		if (Frame\Filesystem::existsFile($file_ht)) {
			if ($this->htusers!==[]) {
				$content_pw=[];
				ksort($this->htusers);
				foreach ($this->htusers as $user=>$password) {
					$content_pw[]=$user.':'.$password;
				}
				file_put_contents($file_pw, implode("\n", $content_pw));
				Frame\Filesystem::changeFilemode($file_pw, Tools\Configure::getFrameConfigInt('settings_chmod_file'));
				$content_ht="\n\nAuthType Basic\nAuthName \"osWTools\"\nAuthUserFile \"".\osWFrame\Core\Settings::getStringVar('settings_abspath').".htpasswd\"\nrequire valid-user\n\n";
			} else {
				if (Frame\Filesystem::existsFile($file_pw)) {
					Frame\Filesystem::delFile($file_pw);
				}
				$content_ht="\n\n";
			}
			file_put_contents($file_ht, preg_replace('/# osWFrame .htaccess permission begin #(.*)# osWFrame .htaccess permission end #/Uis', '# osWFrame .htaccess permission begin #'.$content_ht.'# osWFrame .htaccess permission end #', file_get_contents($file_ht)));
			Frame\Filesystem::changeFilemode($file_ht, Tools\Configure::getFrameConfigInt('settings_chmod_file'));
		}

		return $this;
	}

	/**
	 * @return object
	 */
	public function checkHTAccess():object {
		$file_ht=\osWFrame\Core\Settings::getStringVar('settings_abspath').'.htaccess';
		if (Frame\Filesystem::getFileModTime($file_ht)<Frame\Filesystem::getFileModTime(__FILE__)) {
			if (Frame\Filesystem::existsFile($file_ht)!==true) {
				file_put_contents($file_ht, "# osWFrame .htaccess permission begin #\n\n# osWFrame .htaccess permission end #\n\n# osWFrame .htaccess block begin #\n\nRewriteEngine on\n\nRewriteRule ^tools.([a-z0-9-_]+).stable$ ?module=tools.$1.stable&%{QUERY_STRING} [L]\nRewriteRule ^tools.([a-z0-9-_]+).stable/$ ?module=tools.$1.stable&%{QUERY_STRING} [L]\nRewriteRule ^tools.([a-z0-9-_]+).stable/([a-z0-9-_]+)$ ?module=tools.$1.stable&action=$2&%{QUERY_STRING} [L]\nRewriteRule ^tools.([a-z0-9-_]+).stable/([a-z0-9-_]+)/$ ?module=tools.$1.stable&action=$2&%{QUERY_STRING} [L]\n\nRewriteRule ^([a-zA-Z0-9-_]+)/([a-zA-Z0-9-]+)?_([0-9]+)$ ?module=$1&element_id=$3&%{QUERY_STRING} [L]\nRewriteRule ^([a-zA-Z0-9-_]+)$ ?module=$1&%{QUERY_STRING} [L]\n\nErrorDocument 400 ?module=_errorlogger&error_status=400\nErrorDocument 401 ?module=_errorlogger&error_status=401\nErrorDocument 402 ?module=_errorlogger&error_status=402\nErrorDocument 403 ?module=_errorlogger&error_status=403\nErrorDocument 404 ?module=_errorlogger&error_status=404\nErrorDocument 405 ?module=_errorlogger&error_status=405\nErrorDocument 406 ?module=_errorlogger&error_status=406\nErrorDocument 407 ?module=_errorlogger&error_status=407\nErrorDocument 408 ?module=_errorlogger&error_status=408\nErrorDocument 409 ?module=_errorlogger&error_status=409\nErrorDocument 410 ?module=_errorlogger&error_status=410\nErrorDocument 411 ?module=_errorlogger&error_status=411\nErrorDocument 412 ?module=_errorlogger&error_status=412\nErrorDocument 413 ?module=_errorlogger&error_status=413\nErrorDocument 414 ?module=_errorlogger&error_status=414\nErrorDocument 415 ?module=_errorlogger&error_status=415\nErrorDocument 416 ?module=_errorlogger&error_status=416\nErrorDocument 417 ?module=_errorlogger&error_status=417\n\n# osWFrame .htaccess block end #");
				Frame\Filesystem::changeFilemode($file_ht, Tools\Configure::getFrameConfigInt('settings_chmod_file'));
			} else {
				touch($file_ht);
			}

			$file_pw=\osWFrame\Core\Settings::getStringVar('settings_abspath').'.htpasswd';
			if (Frame\Filesystem::existsFile($file_pw)===true) {
				file_put_contents($file_ht, preg_replace('/# osWFrame .htaccess permission begin #(.*)# osWFrame .htaccess permission end #/Uis', '# osWFrame .htaccess permission begin #'."\n\nAuthType Basic\nAuthName \"osWTools\"\nAuthUserFile \"".\osWFrame\Core\Settings::getStringVar('settings_abspath').".htpasswd\"\nrequire valid-user\n\n".'# osWFrame .htaccess permission end #', file_get_contents($file_ht)));
			}
		}

		return $this;
	}

}

?>