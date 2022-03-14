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

class Math {

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
	 * Seed für den Zufallsgenerator
	 */
	private static $seeded=false;

	/**
	 * Math constructor.
	 */
	private function __construct() {

	}

	/**
	 *
	 * @return float Zufallswert für Initialisierung des Zufallsgenerators.
	 */
	private static function makeSeed():float {
		return microtime(true)*1000000;
	}

	/**
	 * Ein Alias von radomInt.
	 *
	 * @param int $min Linker Rand
	 * @param int $max Rechter Rand
	 * @return int Zufallswert
	 */
	public static function rand(int $min, int $max):int {
		return self::randomInt($min, $max);
	}

	/**
	 * Gibt einen ganzzahligen Zufallswert zurück.
	 *
	 * @param int $min Linker Rand
	 * @param int $max Rechter Rand
	 * @return int Zufallswert
	 */
	public static function randomInt(int $min, int $max):int {
		if (self::$seeded==false) {
			mt_srand(self::makeSeed());
			self::$seeded=true;
		}

		return mt_rand($min, $max);
	}

	/**
	 * Gibt einen Zufallswert zurück.
	 *
	 * @param int $min Linker Rand
	 * @param int $max Rechter Rand
	 * @return int Zufallswert
	 */
	public static function randomFloat($min=0, $max=1):float {
		if (self::$seeded==false) {
			mt_srand(self::makeSeed());
			self::$seeded=true;
		}

		return $min+mt_rand()/self::getrandmax()*($max-$min);
	}

	/**
	 * Zeigt den größtmöglichen Zufallswert an.
	 *
	 * @return int
	 */
	private static function getrandmax():int {
		return mt_getrandmax();
	}

	/**
	 * https://www.php.net/manual/de/function.floatval.php#114486
	 *
	 * @param $num
	 * @return float
	 */
	public static function isFloat($num):float {
		$dotPos=strrpos($num, '.');
		$commaPos=strrpos($num, ',');
		$sep=(($dotPos>$commaPos)&&$dotPos)?$dotPos:((($commaPos>$dotPos)&&$commaPos)?$commaPos:false);

		if (!$sep) {
			return floatval(preg_replace("/[^0-9]/", "", $num));
		}

		return floatval(preg_replace("/[^0-9]/", "", substr($num, 0, $sep)).'.'.preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num))));
	}

	/**
	 * @param float $var
	 * @param int|null $decimals
	 * @param string|null $dec_point
	 * @param string|null $thousands_sep
	 * @return string
	 */
	public static function formatNumber(float $var, int $decimals=null, string $dec_point=null, string $thousands_sep=null):string {
		$locale=localeconv();
		if ($decimals==null) {
			$decimals=$locale['frac_digits'];
		}
		if ($dec_point==null) {
			$dec_point=$locale['decimal_point'];
		}
		if ($thousands_sep==null) {
			$thousands_sep=$locale['thousands_sep'];
		}

		return number_format(floatval($var), $decimals, $dec_point, $thousands_sep);
	}

}

?>