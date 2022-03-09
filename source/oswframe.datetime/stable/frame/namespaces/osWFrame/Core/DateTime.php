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

class DateTime {

	use BaseStaticTrait;

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
	 * @var int
	 */
	protected static $current_time=0;

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
			$time=self::getCurrentTime();
		}

		return gmdate('D, d M Y H:i:s', $time).' GMT';
	}

	/**
	 * @param int $current_time
	 */
	public static function setCurrentTime(int $current_time):void {
		self::$current_time=$current_time;
	}

	/**
	 * @param int $hour
	 * @param int $minute
	 * @param int $second
	 * @param int $month
	 * @param int $day
	 * @param int $year
	 * @return void
	 */
	public static function setCurrentTimeByDate(int $hour, int $minute, int $second, int $month, int $day, int $year):void {
		self::$current_time=mktime($hour, $minute, $second, $month, $day, $year);
	}

	/**
	 * @param int $offset_hour
	 * @param int $offset_minute
	 * @param int $offset_second
	 * @param int $offset_month
	 * @param int $offset_day
	 * @param int $offset_year
	 * @return int
	 */
	public static function createTimeByCurrentTimeOffset(int $offset_hour, int $offset_minute, int $offset_second, int $offset_month, int $offset_day, int $offset_year):int {
		return mktime(date('H', self::getCurrentTime())+$offset_hour, date('i', self::getCurrentTime())+$offset_minute, date('s', self::getCurrentTime())+$offset_second, date('m', self::getCurrentTime())+$offset_month, date('d', self::getCurrentTime())+$offset_day, date('Y', self::getCurrentTime())+$offset_year);
	}

	/**
	 * @return int
	 */
	public static function getCurrentTime():int {
		return self::$current_time;
	}

}

?>