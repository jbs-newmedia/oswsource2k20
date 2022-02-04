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

namespace osWFrame\Core;

class Session {

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
	private const CLASS_RELEASE_VERSION=1;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	/**
	 * @var bool
	 */
	private static bool $issessionstarted=false;

	/**
	 * @var bool
	 */
	private static bool $isnewsession=false;

	/**
	 * @var string
	 */
	private static string $sessionip='';

	/**
	 * @var string
	 */
	private static string $useragent='';

	/**
	 * @var bool
	 */
	private static bool $iscrawler=false;

	/**
	 * Session constructor.
	 */
	private function __construct() {

	}

	/**
	 *
	 * @return bool
	 */
	public static function setEnvironment():bool {
		$session_dir=Settings::getStringVar('settings_abspath').Settings::getStringVar('session_path');
		if (Filesystem::isDir($session_dir)!==true) {
			Filesystem::protectDir($session_dir);
		}
		session_save_path(Settings::getStringVar('settings_abspath').Settings::getStringVar('session_path'));
		$cookie_domain='';
		if (strlen(Settings::getStringVar('project_subdomain'))>0) {
			$cookie_domain.=Settings::getStringVar('project_subdomain').'.';
		}
		$cookie_domain.=Settings::getStringVar('project_domain');
		if (Settings::getStringVar('session_use_only_cookies')===true) {
			ini_set('session.use_only_cookies', 1);
		} else {
			ini_set('session.use_only_cookies', 0);
		}
		session_set_cookie_params(Settings::getIntVar('session_cookie_lifetime'), '/', $cookie_domain, Settings::getBoolVar('session_secure'), Settings::getBoolVar('session_httponly'));
		self::setSessionName();
		self::setSessionIP();
		self::setSessionUA();
		self::setIsCrawler();

		return true;
	}

	/**
	 * @return string
	 */
	public static function getId():string {
		return session_id();
	}

	/**
	 *
	 * @param string $name
	 * @return bool
	 */
	private static function setSessionName(string $name=''):bool {
		if ($name=='') {
			$name=Settings::getStringVar('session_name');
		}
		session_name($name);

		return true;
	}

	/**
	 *
	 * @return string
	 */
	public static function getSessionName():string {
		return session_name();
	}

	/**
	 *
	 * @param string $useragent
	 * @return bool
	 */
	private static function setSessionUA(string $useragent=''):bool {
		if ($useragent=='') {
			$useragent=Misc::getUserAgent();
		}
		self::$useragent=strtolower($useragent);

		return true;
	}

	/**
	 *
	 * @return string
	 */
	public static function getSessionUA():string {
		return self::$useragent;
	}

	/**
	 *
	 * @param string $ip
	 * @return bool
	 */
	private static function setSessionIP(string $ip=''):bool {
		if ($ip=='') {
			$ip=Network::getIPAddress();
		}
		self::$sessionip=$ip;

		return true;
	}

	/**
	 *
	 * @return string
	 */
	public static function getSessionIP():string {
		return self::$sessionip;
	}

	/**
	 * Prüfen und Starten der Session.
	 *
	 * @return bool
	 */
	public static function checkSession():bool {
		if (Settings::getBoolVar('session_gc_probability')===true) {
			$nr=Math::randomInt(1, Settings::getIntVar('session_gc_divisor'));
			$h=round(Settings::getIntVar('session_gc_divisor')/2);
			if ($nr==$h) {
				self::deleteSessions();
			}
		}
		if (self::startSession()) {
			if (self::verifySession()===true) {
				return true;
			} else {
				self::startNewSession();
				if (self::verifySession()===true) {
					return true;
				} else {
					return false;
				}
			}
		}
		return false;
	}

	/**
	 * Löscht alle Sessesion die abgelaufen sind.
	 *
	 * @return bool
	 */
	public static function deleteSessions():bool {
		if ($handle=opendir(Settings::getStringVar('settings_abspath').Settings::getStringVar('session_path'))) {
			while (false!==($file=readdir($handle))) {
				if (($file!='.')&&($file!='..')&&($file!='.htaccess')) {
					self::deleteSession(Settings::getStringVar('session_path').$file);
				}
			}
			closedir($handle);
		}

		return true;
	}

	/**
	 * Löscht eine Session.
	 *
	 * @param string $session Zu löschende Session
	 * @param bool $checktime Prüft ob die Session abgelaufen ist
	 * @return bool
	 */
	public static function deleteSession(string $session, bool $checktime=true):bool {
		if (file_exists(Settings::getStringVar('settings_abspath').$session)) {
			if ($checktime===true) {
				if (filemtime(Settings::getStringVar('settings_abspath').$session)<(time()-Settings::getIntVar('session_lifetime'))) {
					Filesystem::unlink(Settings::getStringVar('settings_abspath').$session);
				}
			} else {
				Filesystem::unlink(Settings::getStringVar('settings_abspath').$session);
			}
		}

		return true;
	}

	/**
	 * Startet eine Session.
	 *
	 * @return bool
	 */
	private static function startSession():bool {
		if (session_start()) {
			if (!isset($_SESSION['sessionstart'])) {
				session_regenerate_id(true);
			}
			self::setSessionStarted(true);

			return true;
		}

		return false;
	}

	/**
	 * Startet eine neue Session.
	 *
	 * @return bool
	 */
	private static function startNewSession():bool {
		$time=time();
		self::setNullSession();
		self::setNewSession(true);
		self::setStringVar('useragent', self::getSessionUA());
		self::setIntVar('sessionstart', $time);
		self::setStringVar('sessionip', self::getSessionIP());
		self::setIntVar('sessionlastcheck', $time);

		return true;
	}

	/**
	 *
	 * @param bool $value
	 * @return bool
	 */
	private static function setNewSession(bool $value):bool {
		if ($value===true) {
			self::$isnewsession=true;
		} else {
			self::$isnewsession=false;
		}

		return true;
	}

	/**
	 *
	 * @return bool
	 */
	public static function isNewSession():bool {
		if (self::$isnewsession===true) {
			return true;
		}

		return false;
	}

	/**
	 *
	 * @return bool
	 */
	public static function setNullSession():bool {
		$_SESSION=[];

		return true;
	}

	/**
	 *
	 * @param bool $value
	 * @return bool
	 */
	private static function setSessionStarted(bool $value):bool {
		if ($value===true) {
			self::$issessionstarted=true;
		} else {
			self::$issessionstarted=false;
		}

		return true;
	}

	/**
	 *
	 * @return bool
	 */
	public static function getSessionStarted():bool {
		if (self::$issessionstarted===true) {
			return true;
		}

		return false;
	}

	/**
	 *
	 * @return bool
	 */
	private static function verifySession():bool {
		if (self::getIntVar('sessionlastcheck')<(time()-Settings::getIntVar('session_lifetime'))) {
			return false;
		}
		if ((Settings::getBoolVar('session_verifyua')===true)&&(self::getStringVar('useragent')!==self::getSessionUA())) {
			return false;
		}
		if (strlen(Settings::getStringVar('session_verifyip'))>0) {
			if (Network::verifyIP(self::getSessionIp(), self::getStringVar('sessionip'), Settings::getStringVar('session_verifyip'))!==true) {
				return false;
			}
		}
		self::setIntVar('sessionlastcheck', time());

		return true;
	}

	/**
	 *
	 * @param string $name
	 * @return bool
	 */
	public static function isVar(string $name):bool {
		if ((self::getSessionStarted()===true)&&(isset($_SESSION[$name]))) {
			return true;
		}

		return false;
	}

	/**
	 *
	 * @param string $name
	 * @param bool $value
	 * @return bool
	 */
	public static function setBoolVar(string $name, bool $value):bool {
		if (self::getSessionStarted()===true) {
			$_SESSION[$name]=$value;

			return true;
		}

		return false;
	}

	/**
	 *
	 * @param string $name
	 * @param string $value
	 * @return bool
	 */
	public static function setStringVar(string $name, string $value):bool {
		if (self::getSessionStarted()===true) {
			$_SESSION[$name]=$value;

			return true;
		}

		return false;
	}

	/**
	 *
	 * @param string $name
	 * @param int $value
	 * @return bool
	 */
	public static function setIntVar(string $name, int $value):bool {
		if (self::getSessionStarted()===true) {
			$_SESSION[$name]=$value;

			return true;
		}

		return false;
	}

	/**
	 *
	 * @param string $name
	 * @param float $value
	 * @return bool
	 */
	public static function setFloatVar(string $name, float $value):bool {
		if (self::getSessionStarted()===true) {
			$_SESSION[$name]=$value;

			return true;
		}

		return false;
	}

	/**
	 *
	 * @param string $name
	 * @param array $value
	 * @return bool
	 */
	public static function setArrayVar(string $name, array $value):bool {
		if (self::getSessionStarted()===true) {
			$_SESSION[$name]=$value;

			return true;
		}

		return false;
	}

	/**
	 *
	 * @param string $name
	 * @return bool|null
	 */
	public static function getBoolVar(string $name):?bool {
		if (self::existVar($name)===true) {
			return $_SESSION[$name];
		}

		return null;
	}

	/**
	 *
	 * @param string $name
	 * @return string|null
	 */
	public static function getStringVar(string $name):?string {
		if (self::existVar($name)===true) {
			return $_SESSION[$name];
		}

		return null;
	}

	/**
	 *
	 * @param string $name
	 * @return int|null
	 */
	public static function getIntVar(string $name):?int {
		if (self::existVar($name)===true) {
			return $_SESSION[$name];
		}

		return null;
	}

	/**
	 *
	 * @param string $name
	 * @return float|null
	 */
	public static function getFloatVar(string $name):?float {
		if (self::existVar($name)===true) {
			return $_SESSION[$name];
		}

		return null;
	}

	/**
	 *
	 * @param string $name
	 * @return array|null
	 */
	public static function getArrayVar(string $name):?array {
		if (self::existVar($name)===true) {
			return $_SESSION[$name];
		}

		return null;
	}

	/**
	 *
	 * @param string $name
	 * @return bool
	 */
	public static function removeVar(string $name):bool {
		if (self::existVar($name)===true) {
			unset($_SESSION[$name]);

			return true;
		}

		return false;
	}

	/**
	 *
	 * @param string $name
	 * @return bool
	 */
	public static function existVar(string $name):bool {
		if ((self::getSessionStarted()===true)&&(isset($_SESSION[$name]))) {
			return true;
		}

		return false;
	}

	/**
	 *
	 * @return int
	 */
	public static function getSessionLifetime():int {
		return (self::getIntVar('sessionlastcheck')+Settings::getIntVar('session_lifetime'));
	}

	/**
	 *
	 * @param bool $iscrawler
	 */
	private static function setIsCrawler(bool $iscrawler=null):bool {
		if ($iscrawler=='') {
			$iscrawler=Misc::checkCrawler();
		}
		self::$iscrawler=$iscrawler;

		return true;
	}

	/**
	 *
	 * @return bool
	 */
	public static function getIsCrawler():bool {
		return self::$iscrawler;
	}

}

?>