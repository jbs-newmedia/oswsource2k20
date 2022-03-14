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

namespace osWFrame\Core;

class Language {

	use BaseStaticTrait;
	use BaseVarStaticTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=1;

	/**
	 * Minor-Version der Klasse.
	 */
	private const CLASS_MINOR_VERSION=2;

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
	 * Array zum Speichern der verfügbaren Sprachen.
	 *
	 * @var array
	 */
	protected static array $languages_available=[];

	/**
	 * @var array
	 */
	protected static $language_vars=[];

	/**
	 * @var array
	 */
	protected static array $module2name=[];

	/**
	 * @var array
	 */
	protected static array $name2module=[];

	/**
	 * @var string
	 */
	protected static string $current_language='';

	/**
	 * Language constructor.
	 */
	private function __construct() {

	}

	/**
	 * Setzt die verfügbaren Sprachen.
	 *
	 * @param array $languages_available
	 * @return bool
	 */
	public static function setAvailableLanguages(array $languages_available):bool {
		foreach ($languages_available as $language) {
			self::$languages_available[$language]=$language;
		}

		return true;
	}

	/**
	 * @return array
	 */
	public static function getAvailableLanguages():array {
		return self::$languages_available;
	}

	/**
	 * @param string $language
	 * @return bool
	 */
	public static function initLanguage(string $language=''):bool {
		if ($language=='') {
			$language=Settings::getStringVar('project_default_language');
		}

		return self::setCurrentLanguage($language);
	}

	/**
	 * @param string $language
	 * @return bool
	 */
	public static function setCurrentLanguage(string $language):bool {
		if (!isset(self::$languages_available[$language])) {
			return false;
		}
		self::$current_language=$language;

		return true;
	}

	/**
	 * @param string $format
	 * @return string
	 */
	public static function getCurrentLanguage(string $format='full'):string {
		switch ($format) {
			case 'short':
				return self::getShortLanguage();
				break;
			case 'full':
			default:
				return self::$current_language;
		}
	}

	/**
	 * @param string $language
	 * @return string
	 */
	public static function getShortLanguage(string $language=''):string {
		if ($language=='') {
			$language=self::$current_language;
		}

		return substr($language, 0, strpos($language, '_'));
	}

	/**
	 * @param string $module
	 * @param string $name
	 * @return bool
	 */
	public static function setModuleName(string $module, string $name):bool {
		self::$module2name[$module]=$name;
		self::$name2module[$name]=$module;

		return true;
	}

	/**
	 * @param array $module2name
	 * @return bool
	 */
	public static function setModuleNames(array $module2name):bool {
		foreach ($module2name as $module=>$name) {
			self::setModuleName($module, $name);
		}

		return true;
	}

	/**
	 * @param string $module
	 * @return string
	 */
	public static function getModuleName(string $module):string {
		if (!isset(self::$module2name)) {
			return $module;
		}
		if (!isset(self::$module2name[$module])) {
			return $module;
		}

		return self::$module2name[$module];
	}

	/**
	 * @param string $name
	 * @return string
	 */
	public static function getNameModule(string $name):string {
		if (!isset(self::$name2module)) {
			return $name;
		}
		if (!isset(self::$name2module[$name])) {
			return $name;
		}

		return self::$name2module[$name];
	}

	/**
	 * @param string $module
	 * @return string
	 */
	private static function getModuleByShort($module='project'):string {
		if ($module=='project') {
			return Settings::getStringVar('project_default_module');
		} elseif ($module=='default') {
			return Settings::getStringVar('frame_default_module');
		} elseif ($module=='current') {
			return Settings::getStringVar('frame_current_module');
		} else {
			return $module;
		}
	}

	/**
	 * @param string $file
	 * @param $module
	 * @param $dir
	 * @return bool
	 */
	public static function loadLanguageFile(string $file, $module='project', $dir='modules'):bool {
		$module=self::getModuleByShort($module);
		$filename=Settings::getStringVar('settings_abspath');
		if ($dir!='') {
			$filename.=$dir.DIRECTORY_SEPARATOR;
		}
		if ($module!='') {
			$filename.=$module.DIRECTORY_SEPARATOR;
		}
		$filename.='lng'.DIRECTORY_SEPARATOR.self::getCurrentLanguage().DIRECTORY_SEPARATOR.$file.'.json';
		if (Filesystem::existsFile($filename)) {
			$json=json_decode(file_get_contents($filename), true);
			if (isset($json['var'])) {
				foreach ($json['var'] as $key=>$value) {
					self::$language_vars[$key]=$value;
				}
			}

			if (isset($json['module'])) {
				self::setModuleNames($json['module']);
			}

			return true;
		}

		return false;
	}

	/**
	 * @param string $var
	 * @return string
	 */
	public static function getLanguageVar(string $var):string {
		if (isset(self::$language_vars[$var])) {
			return self::$language_vars[$var];
		}

		return $var;
	}

	/**
	 * @param string $url
	 * @param string $language_value
	 * @param string $language_var
	 * @return string
	 */
	public static function addLanguage2Url(string $url, string $language_value, string $language_var='language'):string {
		if (strpos($url, '?')==0) {
			$url.='?';
		} else {
			$url.='&';
		}
		$url.=$language_var.'='.$language_value;

		return $url;
	}

}

?>