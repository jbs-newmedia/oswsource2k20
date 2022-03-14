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

class DateTime {

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
	 * DateTime constructor.
	 */
	private function __construct() {

	}

	/**
	 * Gibt das aktuelle Datum und die aktuelle Uhrzeit als GMT aus.
	 *
	 * @param number $time
	 * @return string
	 */
	public static function convertTimeStamp2GM($time=0):string {
		if ($time==0) {
			$time=time();
		}

		return gmdate('D, d M Y H:i:s', $time).' GMT';
	}

}

?>