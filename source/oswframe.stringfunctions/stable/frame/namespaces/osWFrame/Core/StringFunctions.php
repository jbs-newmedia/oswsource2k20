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

class StringFunctions {

	use BaseStaticTrait;

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
	private const CLASS_RELEASE_VERSION=1;

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
		$hashed_string='';
		for ($i=0; $i<($salt_length*3); $i++) {
			$hashed_string.=Math::randomInt(0, 9);
		}
		if (!in_array($algo, ['md5', 'sha1', 'sha256', 'sha384', 'sha512', 'ripemd128', 'ripemd160', 'ripemd256', 'ripemd320', 'whirlpool'])) {
			$algo='sha512';
		}
		$salt=substr(hash($algo, $hashed_string), 0, $salt_length);
		$hashed_string=hash($algo, $salt.$string).':'.$salt;

		return $hashed_string;
	}

	/**
	 * @param string $password
	 * @param string $algo
	 * @return string
	 */
	public static function hashPassword(string $password, string $algo=PASSWORD_DEFAULT):string {
		if (in_array($algo, [PASSWORD_ARGON2I, PASSWORD_ARGON2ID, PASSWORD_BCRYPT])) {
			return password_hash($password, $algo);
		}

		return password_hash($password, PASSWORD_DEFAULT);
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

	/**
	 * @param string $string
	 * @param int $length
	 * @param string $dots
	 * @return string
	 */
	public static function truncateString(string $string, int $length, string $dots='...') {
		return (mb_strlen($string)>$length)?mb_substr($string, 0, $length-mb_strlen($dots)).$dots:$string;
	}

	/**
	 * @param string $string
	 * @param int $count
	 * @param string $dots
	 * @return string
	 */
	public static function truncateStringByWordCount(string $string, int $count, string $dots=' ...') {
		$words=explode(' ', $string);
		if (count($words)>$count) {
			return implode(' ', array_slice($words, 0, $count)).$dots;
		}

		return $string;
	}

}

?>