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
	 * Array zum Speichern der verfügbaren Sprachen.
	 *
	 * @var array
	 */
	private static array $languages_available=[];

	/**
	 * @var array
	 */
	private static array $module2name=[];

	/**
	 * @var array
	 */
	private static array $name2module=[];

	/**
	 * @var string
	 */
	private static string $current_language='';

	/**
	 * Language constructor.
	 */
	private function __construct() {

	}

	/**
	 * Setzt die verfügabren Sprachen.
	 *
	 * @param array $languages_available
	 * @return bool
	 */
	public static function setAvailableLanguages(array $languages_available):bool {
		self::$languages_available=$languages_available;

		return true;
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
				return substr(self::$current_language, 0, strpos(self::$current_language, '_'));
				break;
			case 'full':
			default:
				return self::$current_language;
		}

	}

	/**
	 * @param string $module
	 * @param string $name
	 * @param string $current_language
	 * @return bool
	 */
	public static function setModuleName(string $module, string $name, string $current_language=''):bool {
		if ($current_language=='') {
			$current_language=self::getCurrentLanguage('short');
		}
		self::$module2name[$current_language][$module]=$name;
		self::$name2module[$current_language][$name]=$module;

		return true;
	}

	/**
	 * @param array $module2name
	 * @param string $current_language
	 * @return bool
	 */
	public static function setModuleNames(array $module2name, string $current_language=''):bool {
		if ($current_language=='') {
			$current_language=self::getCurrentLanguage('short');
		}
		foreach ($module2name as $module=>$name) {
			self::setModuleName($module, $name, $current_language);
		}

		return true;
	}

	/**
	 * @param string $module
	 * @param string $current_language
	 * @return string
	 */
	public static function getModuleName(string $module, string $current_language=''):string {
		if ($current_language=='') {
			$current_language=self::getCurrentLanguage('short');
		}
		if (!isset(self::$module2name[$current_language])) {
			return $module;
		}
		if (!isset(self::$module2name[$current_language][$module])) {
			return $module;
		}

		return self::$module2name[$current_language][$module];
	}

	/**
	 * @param string $name
	 * @param string $current_language
	 * @return string
	 */
	public static function getNameModule(string $name, string $current_language=''):string {
		if ($current_language=='') {
			$current_language=self::getCurrentLanguage('short');
		}
		if (!isset(self::$name2module[$current_language])) {
			return $name;
		}
		if (!isset(self::$name2module[$current_language][$name])) {
			return $name;
		}

		return self::$name2module[$current_language][$name];
	}

}

?>