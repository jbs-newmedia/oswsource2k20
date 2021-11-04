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

class StringFunctions {

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
	private const CLASS_RELEASE_VERSION=0;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	/**
	 * StringFunctions constructor.
	 */
	private function __construct() {

	}

	/**
	 *
	 * @param string $text
	 * @param array $vars
	 * @return string
	 */
	public static function parseTextWithVars(string $text, array $vars):string {
		foreach ($vars as $key=>$value) {
			if (!is_array($value)&&(!is_object($value))) {
				$text=str_replace('$'.$key.'$', $value, $text);
			}
		}

		return $text;
	}

	/**
	 *
	 * @param string $string
	 * @param string $algo
	 * @param int $salt_length
	 * @return string
	 */
	public static function encryptString(string $string, string $algo=PASSWORD_DEFAULT, int $salt_length=6):string {
		if (in_array($algo, ['md5', 'sha1', 'sha256', 'sha384', 'sha512', 'ripemd128', 'ripemd160', 'ripemd256', 'ripemd320', 'whirlpool'])) {
			$password='';
			for ($i=0; $i<($salt_length*3); $i++) {
				$password.=Math::randomInt(0, 9);
			}
			$salt=substr(hash($algo, $password), 0, $salt_length);

			return hash($algo, $salt.$string).':'.$salt;
		} elseif (in_array($algo, [PASSWORD_ARGON2I, PASSWORD_ARGON2ID, PASSWORD_BCRYPT])) {
			return password_hash($string, $algo);
		}

		return password_hash($string, PASSWORD_DEFAULT);
	}

	/**
	 * @param string $string
	 * @return string
	 */
	public static function outputUrlString(string $string):string {
		$german_search=["Ä", "ä", "Ü", "ü", "Ö", "ö", "ß"];
		$german_replace=["Ae", "ae", "Ue", "ue", "Oe", "oe", "ss"];
		$string=str_replace($german_search, $german_replace, $string);
		$string=preg_replace('/[^a-zA-Z0-9]/', ' ', $string);
		$string=preg_replace('/\s\s+/', ' ', $string);
		$string=str_replace(' ', '-', trim($string));

		return urlencode($string);
	}

	/**
	 * @param string $text
	 * @return string
	 */
	public static function convertHTML2Plain(string $text):string {
		$text=preg_replace('/<a[^<]*href="([^"]+)"[^<]*<\/a>/', "\\1", $text);
		$text=str_replace('&amp;', '&', $text);
		$text=str_replace('<br/>', "\n", $text);
		$text=str_replace('<br/>', "\n", $text);

		return strip_tags($text);
	}

}

?>