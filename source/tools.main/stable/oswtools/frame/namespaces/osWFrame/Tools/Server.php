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

use osWFrame\Core as Frame;

class Server {

	use Frame\BaseStaticTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=1;

	/**
	 * Minor-Version der Klasse.
	 */
	private const CLASS_MINOR_VERSION=3;

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
	protected static array $serverlist=[];

	/**
	 * @var array
	 */
	protected static array $serverlist_connected=[];

	/**
	 * @var array
	 */
	protected static array $packagelist=[];

	/**
	 * @var array
	 */
	protected static array $licenselist=[];

	/**
	 * @var int
	 */
	protected static int $cachetime=3600;

	/**
	 * @var string
	 */
	protected static string $frame_key='';


	/**
	 * @var string
	 */
	protected static string $account_email='';

	/**
	 * Server constructor.
	 */
	private function __construct() {

	}

	/**
	 * @param string $current_serverlist
	 * @return array
	 */
	public static function getConnectedServer(string $current_serverlist):array {
		if (!isset(self::$serverlist[$current_serverlist])) {
			self::readServerList();
		}

		if (!isset(self::$serverlist_connected[$current_serverlist])) {
			self::connectServer($current_serverlist);
		}

		if (!isset(self::$serverlist_connected[$current_serverlist])) {
			return [];
		}

		return self::$serverlist_connected[$current_serverlist];
	}

	/**
	 * @param string $current_serverlist
	 * @return bool
	 */
	public static function connectServer(string $current_serverlist):bool {
		self::readServerList();
		if ((!isset(self::$serverlist[$current_serverlist]))||(!isset(self::$serverlist[$current_serverlist]['data']))) {
			return false;
		}
		foreach (self::$serverlist[$current_serverlist]['data'] as $server_id=>$server_data) {
			$_content=self::getUrlData($server_data['server_url']);
			if ((strlen($_content)>=26)&&(strlen($_content)<=128)) {
				if (stristr($_content, 'osWFrame Release Server')) {
					self::$serverlist_connected[$current_serverlist]=$server_data;
					self::$serverlist_connected[$current_serverlist]['connected']=true;
					self::$serverlist_connected[$current_serverlist]['server_name_real']=$_content;

					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public static function readServerList():bool {
		$directory=Frame\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'serverlist'.DIRECTORY_SEPARATOR;
		if (self::$serverlist==[]) {
			$handle=opendir($directory);
			while ($file=readdir($handle)) {
				if (($file!='.')&&($file!='..')) {
					$currentserverlist=substr($file, 0, -5);
					$jsonfile=$directory.$file;
					if (Frame\Filesystem::existsFile($jsonfile)) {
						self::$serverlist[$currentserverlist]=json_decode(file_get_contents($jsonfile), true);
					}
				}
				ksort(self::$serverlist);
			}
		}

		return true;
	}

	/**
	 * @return array
	 */
	public static function getServerList() {
		if (self::$serverlist==[]) {
			self::readServerList();
		}

		return self::$serverlist;
	}

	/**
	 * @param $file
	 * @return string
	 */
	public static function getUrlData($file):string {
		if (!strpos($file, '?')) {
			$file.='?frame_key='.urlencode(self::getFrameKey());
		} else {
			$file.='&frame_key='.urlencode(self::getFrameKey());
		}
		$file.='&account_email='.sha1(self::getFrameKey().'#'.self::getAccountEMail());
		if (function_exists('curl_init')) {
			$res=curl_init();
			curl_setopt($res, CURLOPT_URL, $file);
			curl_setopt($res, CURLOPT_RETURNTRANSFER, 1);
			$file=curl_exec($res);
			curl_close($res);
		} else {
			$res=@fopen($file, 'r');
			if (!$res) {
				$file='';
			} else {
				$file='';
				while (feof($res)!=1) {
					$file.=fgets($res, 1024);
				}
				fclose($res);
			}
		}

		return $file;
	}

	/**
	 * @return string
	 */
	public static function getServerName():string {
		if (isset($_SERVER['SERVER_NAME'])) {
			return $_SERVER['SERVER_NAME'];
		}

		return 'undefined';
	}

	/**
	 * @param bool $force
	 * @return string
	 */
	public static function getFrameKey(bool $force=false):string {
		if ((self::$frame_key=='')||($force===true)) {
			$file=Frame\Settings::getStringVar('settings_abspath').'frame.key';
			if (Frame\Filesystem::existsFile($file)===true) {
				self::$frame_key=trim(file_get_contents($file));
			} else {
				self::$frame_key='unset';
			}
		}

		return self::$frame_key;
	}

	/**
	 * @param bool $force
	 * @return string
	 */
	public static function getAccountEMail(bool $force=false):string {
		if ((self::$account_email=='')||($force===true)) {
			$file=Frame\Settings::getStringVar('settings_abspath').'account.email';
			if (Frame\Filesystem::existsFile($file)===true) {
				self::$account_email=trim(file_get_contents($file));
			} else {
				self::$account_email='unset';
			}
		}

		return self::$account_email;
	}

	/**
	 * @param bool $force
	 * @return bool
	 */
	public static function updatePackageList(bool $force=false):bool {
		self::readServerList();
		foreach (array_keys(self::$serverlist) as $current_serverlist) {
			$file=Frame\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'packagelist'.DIRECTORY_SEPARATOR.$current_serverlist.'.json';
			if (($force===true)||(((filemtime($file))<(time()-(self::$cachetime)))||(filesize($file)<32))) {
				$server_data=self::getConnectedServer($current_serverlist);
				if ($server_data!=[]) {
					$json=self::getUrlData($server_data['server_url'].'?action=server_packages');
					$data=json_decode($json);
					if ($data!==null) {
						file_put_contents($file, $json);
					}
				}
			}
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public static function readPackageList():bool {
		self::updatePackageList();
		if (self::$packagelist==[]) {
			foreach (array_keys(self::$serverlist) as $current_serverlist) {
				self::$packagelist[$current_serverlist]=[];
				$file=Frame\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'packagelist'.DIRECTORY_SEPARATOR.$current_serverlist.'.json';
				$data=json_decode(file_get_contents($file), true);
				if ($data!==null) {
					self::$packagelist[$current_serverlist]=$data;
				}
			}
		}

		return true;
	}

	/**
	 * @param string $current_serverlist
	 * @return array
	 */
	public static function getPackageList(string $current_serverlist=''):array {
		self::readPackageList();

		if ($current_serverlist=='') {
			return self::$packagelist;
		}

		if (isset(self::$packagelist[$current_serverlist])) {
			return self::$packagelist[$current_serverlist];
		}

		return [];
	}

	/**
	 * @return bool
	 */
	public static function readLicenseList():bool {
		if (self::$licenselist==[]) {
			$serverlist=self::getServerList();
			foreach ($serverlist as $current_serverlist=>$serverlist_details) {
				self::$licenselist[$current_serverlist]=[];
				$server_data=Server::getConnectedServer($current_serverlist);
				if ((isset($server_data['connected']))&&($server_data['connected']===true)) {
					self::$licenselist[$current_serverlist]['server_list']=$serverlist_details['info']['name'];
					self::$licenselist[$current_serverlist]['frame_key']=self::getFrameKey();
					self::$licenselist[$current_serverlist]['account_email']=self::getAccountEMail();
					self::$licenselist[$current_serverlist]['license_key']=sha1($current_serverlist.'#'.self::$licenselist[$current_serverlist]['frame_key'].'#'.self::$licenselist[$current_serverlist]['account_email']);
				}
			}
			ksort(self::$licenselist[$current_serverlist]);
		}

		return true;
	}

	/**
	 * @param string $current_serverlist
	 * @return array
	 */
	public static function getLicenseList(string $current_serverlist=''):array {
		self::readLicenseList();

		if ($current_serverlist=='') {
			return self::$licenselist;
		}

		if (isset(self::$licenselist[$current_serverlist])) {
			return self::$licenselist[$current_serverlist];
		}

		return [];
	}

}

?>