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

class Settings {

	use BaseStaticTrait;
	use BaseVarStaticTrait;

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
	private const CLASS_RELEASE_VERSION=2;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	/**
	 * Speichert die Action, Default ist immer ''.
	 *
	 * @var string
	 */
	private static string $action='';

	/**
	 * Array zum Speichern der Config-Dateien.
	 *
	 * @var array
	 */
	private static array $config_files=[];

	/**
	 * Settings constructor.
	 */
	private function __construct() {

	}

	/**
	 * Lädt die Konfiguration des Frameworks.
	 *
	 * @return bool
	 */
	public static function loadDefaultConfigure():bool {
		self::setStringVar('settings_runmode', 'live');
		if (file_exists(self::getStringVar('settings_abspath').'frame'.DIRECTORY_SEPARATOR.'configure.dev.php')) {
			require_once(self::getStringVar('settings_abspath').'frame'.DIRECTORY_SEPARATOR.'configure.dev.php');
			self::addConfigFile(self::getStringVar('settings_abspath').'frame'.DIRECTORY_SEPARATOR.'configure.dev.php');
			self::setStringVar('settings_runmode', 'dev');

			return true;
		} elseif (file_exists(self::getStringVar('settings_abspath').'frame'.DIRECTORY_SEPARATOR.'configure.php')) {
			require_once(self::getStringVar('settings_abspath').'frame'.DIRECTORY_SEPARATOR.'configure.php');
			self::addConfigFile(self::getStringVar('settings_abspath').'frame'.DIRECTORY_SEPARATOR.'configure.php');

			return true;
		}

		return false;
	}

	/**
	 * Lädt weitere Konfigurationen für Projekte usw.
	 *
	 * @param string $path
	 * @param string $sub
	 * @return bool
	 */
	public static function loadConfigure(string $path, string $sub=''):bool {
		$file=self::getStringVar('settings_abspath').$path.DIRECTORY_SEPARATOR.'configure';
		if ($sub!='') {
			$file.='.'.$sub;
		}
		$file_dev=$file.'-dev.php';
		$file_prod=$file.'.php';
		if (file_exists($file_dev)) {
			self::setStringVar('settings_runmode', 'dev');
			require_once $file_dev;
			self::addConfigFile($file_dev);

			return true;
		} elseif (file_exists($file_prod)) {
			require_once $file_prod;
			self::addConfigFile($file_prod);

			return true;
		}

		return false;
	}

	/**
	 * Legt regionale (locale) Einstellungen fest.
	 *
	 * @return string
	 */
	public static function setLocale():string {
		if (Language::getStringVar('locale')!==null) {
			$locale=Language::getVar('locale');
		} else {
			$locale=self::getStringVar('project_locale');
		}

		return setlocale(LC_ALL, $locale);
	}

	/**
	 * Gibt die regionale (locale) Einstellung zurück.
	 *
	 * @return string
	 */
	public static function getLocale():string {
		return setlocale(LC_ALL, 0);
	}

	/**
	 * Setzt die Standardzeitzone, die von allen Datums- und Zeitfunktionen benutzt wird.
	 *
	 * @return bool
	 */
	public static function setTimezone():bool {
		if (Language::getStringVar('timezone')!==null) {
			$timezone=Language::getVar('timezone');
		} else {
			$timezone=self::getStringVar('project_timezone');
		}

		return date_default_timezone_set($timezone);
	}

	/**
	 * Setzt die Umgebungsvariablen des Projektes.
	 *
	 * @return bool
	 */
	public static function setProjectEnvironment():bool {
		self::setStringVar('frame_current_module', self::getStringVar('project_default_module'));
		Language::setAvailableLanguages(self::getArrayVar('language_availablelanguages'));
		$domain='';
		if (strlen(self::getStringVar('project_subdomain'))>0) {
			$domain.=self::getStringVar('project_subdomain').'.';
		}
		$domain.=self::getStringVar('project_domain');
		$project_domain_full='';
		if (self::getBoolVar('settings_ssl')===true) {
			$project_domain_full.='https://'.$domain;
			if (self::getIntVar('settings_ssl_port')!=self::getIntVar('project_ssl_port')) {
				$project_domain_full.=':'.self::getIntVar('project_port');
			}
		} else {
			$project_domain_full.='http://'.$domain;
			if (self::getIntVar('settings_port')!=self::getIntVar('project_port')) {
				$project_domain_full.=':'.self::getIntVar('project_port');
			}
		}
		$project_domain_full.='/';
		if (strlen(self::getStringVar('project_path'))>0) {
			$project_domain_full.=self::getStringVar('project_path').'/';
		}
		self::setStringVar('project_domain_full', $project_domain_full);
		Language::initLanguage(Settings::getStringVar('project_default_language'));

		return true;
	}

	/**
	 *
	 * @return string
	 */
	public static function getBaseUrl():string {
		return self::getStringVar('project_domain_full');
	}

	/**
	 * Entfernt MagicQuotes im Array
	 *
	 * @param array $array
	 * @return bool
	 */
	private static function stripMagicQuotes(array $array):bool {
		if (!is_array($array)||(sizeof($array)<1)) {
			return false;
		}
		foreach ($array as $key=>$value) {
			if (is_array($value)) {
				self::stripMagicQuotes($array[$key]);
			} else {
				$array[$key]=stripslashes($value);
			}
		}

		return true;
	}

	/**
	 *
	 * @param string $key
	 * @param bool $default
	 * @param string $order
	 * @param int $index
	 * @return bool
	 */
	public static function catchBoolValue(string $key, bool $default=false, string $order='gpc', int $index=null):bool {
		return boolval(self::catchValue($key, $default, $order, $index));
	}

	/**
	 *
	 * @param string $key
	 * @param string $default
	 * @param string $order
	 * @param int $index
	 * @return string
	 */
	public static function catchStringValue(string $key, string $default='', string $order='gpc', int $index=null):string {
		return self::catchValue($key, $default, $order, $index);
	}

	/**
	 *
	 * @param string $key
	 * @param int $default
	 * @param string $order
	 * @param int $index
	 * @return int
	 */
	public static function catchIntValue(string $key, int $default=0, string $order='gpc', int $index=null):int {
		return intval(self::catchValue($key, $default, $order, $index));
	}

	/**
	 *
	 * @param string $key
	 * @param float $default
	 * @param string $order
	 * @param int $index
	 * @return float
	 */
	public static function catchFloatValue(string $key, float $default=0.0, string $order='gpc', int $index=null):float {
		return floatval(self::catchValue($key, $default, $order, $index));
	}

	/**
	 *
	 * @param string $key
	 * @param array $default
	 * @param string $order
	 * @param int $index
	 * @return array
	 */
	public static function catchArrayValue(string $key, array $default=[], string $order='gpc', int $index=null):array {
		return self::catchValue($key, $default, $order, $index);
	}

	/**
	 *
	 * @param string $key
	 * @param bool $default
	 * @param int $index
	 * @return bool
	 */
	public static function catchBoolPostValue(string $key, bool $default=false, int $index=null):bool {
		return boolval(self::catchValue($key, $default, 'p', $index));
	}

	/**
	 *
	 * @param string $key
	 * @param string $default
	 * @param int $index
	 * @return string
	 */
	public static function catchStringPostValue(string $key, string $default='', int $index=null):string {
		return self::catchValue($key, $default, 'p', $index);
	}

	/**
	 *
	 * @param string $key
	 * @param int $default
	 * @param int $index
	 * @return int
	 */
	public static function catchIntPostValue(string $key, int $default=0, int $index=null):int {
		return intval(self::catchValue($key, $default, 'p', $index));
	}

	/**
	 *
	 * @param string $key
	 * @param float $default
	 * @param int $index
	 * @return float
	 */
	public static function catchFloatPostValue(string $key, float $default=0.0, int $index=null):float {
		return floatval(self::catchValue($key, $default, 'p', $index));
	}

	/**
	 *
	 * @param string $key
	 * @param array $default
	 * @param int $index
	 * @return array
	 */
	public static function catchArrayPostValue(string $key, array $default=[], int $index=null):array {
		return self::catchValue($key, $default, 'p', $index);
	}

	/**
	 *
	 * @param string $key
	 * @param bool $default
	 * @param int $index
	 * @return bool
	 */
	public static function catchBoolGetValue(string $key, bool $default=false, int $index=null):bool {
		return boolval(self::catchValue($key, $default, 'g', $index));
	}

	/**
	 *
	 * @param string $key
	 * @param string $default
	 * @param int $index
	 * @return string
	 */
	public static function catchStringGetValue(string $key, string $default='', int $index=null):string {
		return self::catchValue($key, $default, 'g', $index);
	}

	/**
	 *
	 * @param string $key
	 * @param int $default
	 * @param int $index
	 * @return int
	 */
	public static function catchIntGetValue(string $key, int $default=0, int $index=null):int {
		return intval(self::catchValue($key, $default, 'g', $index));
	}

	/**
	 *
	 * @param string $key
	 * @param float $default
	 * @param int $index
	 * @return float
	 */
	public static function catchFloatGetValue(string $key, float $default=0.0, int $index=null):float {
		return floatval(self::catchValue($key, $default, 'g', $index));
	}

	/**
	 *
	 * @param string $key
	 * @param array $default
	 * @param int $index
	 * @return array
	 */
	public static function catchArrayGetValue(string $key, array $default=[], int $index=null):array {
		return self::catchValue($key, $default, 'g', $index);
	}

	/**
	 *
	 * @param string $key
	 * @param bool $default
	 * @param int $index
	 * @return bool
	 */
	public static function catchBoolCookieValue(string $key, bool $default=false, int $index=null):bool {
		return boolval(self::catchValue($key, $default, 'c', $index));
	}

	/**
	 *
	 * @param string $key
	 * @param string $default
	 * @param int $index
	 * @return string
	 */
	public static function catchStringCookieValue(string $key, string $default='', int $index=null):string {
		return self::catchValue($key, $default, 'c', $index);
	}

	/**
	 *
	 * @param string $key
	 * @param int $default
	 * @param int $index
	 * @return int
	 */
	public static function catchIntCookieValue(string $key, int $default=0, int $index=null):int {
		return intval(self::catchValue($key, $default, 'c', $index));
	}

	/**
	 *
	 * @param string $key
	 * @param float $default
	 * @param int $index
	 * @return float
	 */
	public static function catchFloatCookieValue(string $key, float $default=0.0, int $index=null):float {
		return floatval(self::catchValue($key, $default, 'c', $index));
	}

	/**
	 *
	 * @param string $key
	 * @param array $default
	 * @param int $index
	 * @return array
	 */
	public static function catchArrayCookieValue(string $key, array $default=[], int $index=null):array {
		return self::catchValue($key, $default, 'c', $index);
	}

	/**
	 *
	 * @param string $key
	 * @param bool $default
	 * @param int $index
	 * @return bool
	 */
	public static function catchBoolSessionValue(string $key, bool $default=false, int $index=null):bool {
		return boolval(self::catchValue($key, $default, 's', $index));
	}

	/**
	 *
	 * @param string $key
	 * @param string $default
	 * @param int $index
	 * @return string
	 */
	public static function catchStringSessionValue(string $key, string $default='', int $index=null):string {
		return self::catchValue($key, $default, 's', $index);
	}

	/**
	 *
	 * @param string $key
	 * @param int $default
	 * @param int $index
	 * @return int
	 */
	public static function catchIntSessionValue(string $key, int $default=0, int $index=null):int {
		return intval(self::catchValue($key, $default, 's', $index));
	}

	/**
	 *
	 * @param string $key
	 * @param float $default
	 * @param int $index
	 * @return float
	 */
	public static function catchFloatSessionValue(string $key, float $default=0.0, int $index=null):float {
		return floatval(self::catchValue($key, $default, 's', $index));
	}

	/**
	 *
	 * @param string $key
	 * @param array $default
	 * @param int $index
	 * @return array
	 */
	public static function catchArraySessionValue(string $key, array $default=[], int $index=null):array {
		return self::catchValue($key, $default, 's', $index);
	}

	/**
	 * Gibt den Inhalt einer GET/POST/FILES/COOKIE/SESSION/SERVER Variablen zurück oder initialisiert sie.
	 *
	 * @param string $key Name der Variable
	 * @param mixed $default Initialisierungswert sofern Variable nicht vorhanden ist
	 * @param string $order Liste und Reihenfolge der Globals (g=GET, p=POST, f=FILE, c=COOKIE, s=SESSION, r=SERVER)
	 * @param null $index wenn gesetzt, dann der Key des Arrays
	 * @return mixed
	 */
	public static function catchValue(string $key, mixed $default='', string $order='gpcs', $index=null):mixed {
		for ($i=0; $i<strlen($order); $i++) {
			switch ($order[$i]) {
				case 'g':
					if ($index!==null) {
						if (isset($_GET[$key][$index])) {
							return $_GET[$key][$index];
						}
					} else {
						if (isset($_GET[$key])) {
							return $_GET[$key];
						}
					}
					break;
				case 'p':
					if ($index!==null) {
						if (isset($_POST[$key][$index])) {
							return $_POST[$key][$index];
						}
					} else {
						if (isset($_POST[$key])) {
							return $_POST[$key];
						}
					}
					break;
				case 'f':
					if ($index!==null) {
						if (isset($_FILES[$key][$index])) {
							return $_FILES[$key][$index];
						}
					} else {
						if (isset($_FILES[$key])) {
							return $_FILES[$key];
						}
					}
					break;
				case 'c':
					if ($index!==null) {
						if (isset($_COOKIE[$key][$index])) {
							return $_COOKIE[$key][$index];
						}
					} else {
						if (isset($_COOKIE[$key])) {
							return $_COOKIE[$key];
						}
					}
					break;
				case 's':
					if ($index!==null) {
						if (isset($_SESSION[$key][$index])) {
							return $_SESSION[$key][$index];
						}
					} else {
						if (isset($_SESSION[$key])) {
							return $_SESSION[$key];
						}
					}
					break;
				case 'r':
					if ($index!==null) {
						if (isset($_SERVER[$key][$index])) {
							return $_SERVER[$key][$index];
						}
					} else {
						if (isset($_SERVER[$key])) {
							return $_SERVER[$key];
						}
					}
					break;
			}
		}

		return $default;
	}

	/**
	 *
	 * @param string $action
	 * @return bool
	 */
	public static function setAction(string $action):bool {
		self::$action=$action;

		return true;
	}

	/**
	 *
	 * @return string
	 */
	public static function getAction():string {
		return self::$action;
	}

	/**
	 *
	 * @param string $content
	 */
	public static function dieScript(string $content='') {
		die($content);
	}

	/**
	 * @return bool
	 */
	public static function checkSlowRunTime():bool {
		if (Debug::calcTimer('scriptload')>self::getFloatVar('settings_slowruntime')) {
			MessageStack::addMessage(self::getNameAsString(), 'slowruntime', ['time'=>time(), 'line'=>__LINE__, 'function'=>__FUNCTION__, 'runtime'=>Debug::calcTimer('scriptload'), 'script'=>$_SERVER['REQUEST_URI']]);

			return false;
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public static function checkHighMemoryUsage():bool {
		if (memory_get_usage()>self::getFloatVar('settings_ramlimit')) {
			MessageStack::addMessage(self::getNameAsString(), 'highmemoryusage', ['time'=>time(), 'line'=>__LINE__, 'function'=>__FUNCTION__, 'memoryusage'=>memory_get_usage(), 'script'=>$_SERVER['REQUEST_URI']]);

			return false;
		}

		return true;
	}

	/**
	 *
	 * @param string $file
	 * @return bool
	 */
	public static function addConfigFile(string $file):bool {
		self::$config_files[md5($file)]=$file;

		return true;
	}

	/**
	 *
	 * @return array
	 */
	public static function getConfigFiles():array {
		return self::$config_files;
	}

}

?>