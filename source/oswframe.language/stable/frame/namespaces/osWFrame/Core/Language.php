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
	private const CLASS_MINOR_VERSION=0;

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

	public static function initLanguage(string $language=''):bool {
		if ($language=='') {
			$language=Settings::getStringVar('project_default_language');
		}

		return self::setCurrentLanguage($language);
	}

	public static function setCurrentLanguage(string $language):bool {
		self::$current_language=$language;

		return true;
	}

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

}

?>