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

	/**
	 * Locale-formatted strftime using \IntlDateFormatter (PHP 8.1 compatible)
	 * This provides a cross-platform alternative to strftime() for when it will be removed from PHP.
	 * Note that output can be slightly different between libc sprintf and this function as it is using ICU.
	 *
	 * Usage:
	 * use function \PHP81_BC\strftime;
	 * echo strftime('%A %e %B %Y %X', new \DateTime('2021-09-28 00:00:00'), 'fr_FR');
	 *
	 * Original use:
	 * \setlocale('fr_FR.UTF-8', LC_TIME);
	 * echo \strftime('%A %e %B %Y %X', strtotime('2021-09-28 00:00:00'));
	 *
	 * @param string $format Date format
	 * @param integer|string|DateTime $timestamp Timestamp
	 * @return string
	 * @author BohwaZ <https://bohwaz.net/>
	 * @source https://gist.github.com/bohwaz/42fc223031e2b2dd2585aab159a20f30
	 */
	public static function strftime(string $format, $timestamp=null, ?string $locale=null):string {
		if (null===$timestamp) {
			$timestamp=new \DateTime;
		} elseif (is_numeric($timestamp)) {
			$timestamp=date_create('@'.$timestamp);

			if ($timestamp) {
				$timestamp->setTimezone(new \DateTimezone(date_default_timezone_get()));
			}
		} elseif (is_string($timestamp)) {
			$timestamp=date_create($timestamp);
		}

		if (!($timestamp instanceof \DateTimeInterface)) {
			throw new \InvalidArgumentException('$timestamp argument is neither a valid UNIX timestamp, a valid date-time string or a DateTime object.');
		}

		$locale=substr((string) $locale, 0, 5);

		$intl_formats=['%a'=>'EEE',    // An abbreviated textual representation of the day	Sun through Sat
			'%A'=>'EEEE',    // A full textual representation of the day	Sunday through Saturday
			'%b'=>'MMM',    // Abbreviated month name, based on the locale	Jan through Dec
			'%B'=>'MMMM',    // Full month name, based on the locale	January through December
			'%h'=>'MMM',    // Abbreviated month name, based on the locale (an alias of %b)	Jan through Dec
		];

		$intl_formatter=function(\DateTimeInterface $timestamp, string $format) use ($intl_formats, $locale) {
			$tz=$timestamp->getTimezone();
			$date_type=\IntlDateFormatter::FULL;
			$time_type=\IntlDateFormatter::FULL;
			$pattern='';

			// %c = Preferred date and time stamp based on locale
			// Example: Tue Feb 5 00:45:10 2009 for February 5, 2009 at 12:45:10 AM
			if ($format=='%c') {
				$date_type=\IntlDateFormatter::LONG;
				$time_type=\IntlDateFormatter::SHORT;
			}
			// %x = Preferred date representation based on locale, without the time
			// Example: 02/05/09 for February 5, 2009
			elseif ($format=='%x') {
				$date_type=\IntlDateFormatter::SHORT;
				$time_type=\IntlDateFormatter::NONE;
			} // Localized time format
			elseif ($format=='%X') {
				$date_type=\IntlDateFormatter::NONE;
				$time_type=\IntlDateFormatter::MEDIUM;
			} else {
				$pattern=$intl_formats[$format];
			}

			return (new \IntlDateFormatter($locale, $date_type, $time_type, $tz, null, $pattern))->format($timestamp);
		};

		// Same order as https://www.php.net/manual/en/function.strftime.php
		$translation_table=[// Day
			'%a'=>$intl_formatter, '%A'=>$intl_formatter, '%d'=>'d', '%e'=>function($timestamp) {
				return sprintf('% 2u', $timestamp->format('j'));
			}, '%j'=>function($timestamp) {
				// Day number in year, 001 to 366
				return sprintf('%03d', $timestamp->format('z')+1);
			}, '%u'=>'N', '%w'=>'w',

			// Week
			'%U'=>function($timestamp) {
				// Number of weeks between date and first Sunday of year
				$day=new \DateTime(sprintf('%d-01 Sunday', $timestamp->format('Y')));

				return sprintf('%02u', 1+($timestamp->format('z')-$day->format('z'))/7);
			}, '%V'=>'W', '%W'=>function($timestamp) {
				// Number of weeks between date and first Monday of year
				$day=new \DateTime(sprintf('%d-01 Monday', $timestamp->format('Y')));

				return sprintf('%02u', 1+($timestamp->format('z')-$day->format('z'))/7);
			},

			// Month
			'%b'=>$intl_formatter, '%B'=>$intl_formatter, '%h'=>$intl_formatter, '%m'=>'m',

			// Year
			'%C'=>function($timestamp) {
				// Century (-1): 19 for 20th century
				return floor($timestamp->format('Y')/100);
			}, '%g'=>function($timestamp) {
				return substr($timestamp->format('o'), -2);
			}, '%G'=>'o', '%y'=>'y', '%Y'=>'Y',

			// Time
			'%H'=>'H', '%k'=>function($timestamp) {
				return sprintf('% 2u', $timestamp->format('G'));
			}, '%I'=>'h', '%l'=>function($timestamp) {
				return sprintf('% 2u', $timestamp->format('g'));
			}, '%M'=>'i', '%p'=>'A', // AM PM (this is reversed on purpose!)
			'%P'=>'a', // am pm
			'%r'=>'h:i:s A', // %I:%M:%S %p
			'%R'=>'H:i', // %H:%M
			'%S'=>'s', '%T'=>'H:i:s', // %H:%M:%S
			'%X'=>$intl_formatter, // Preferred time representation based on locale, without the date

			// Timezone
			'%z'=>'O', '%Z'=>'T',

			// Time and Date Stamps
			'%c'=>$intl_formatter, '%D'=>'m/d/Y', '%F'=>'Y-m-d', '%s'=>'U', '%x'=>$intl_formatter,];

		$out=preg_replace_callback('/(?<!%)(%[a-zA-Z])/', function($match) use ($translation_table, $timestamp) {
			if ($match[1]=='%n') {
				return "\n";
			} elseif ($match[1]=='%t') {
				return "\t";
			}

			if (!isset($translation_table[$match[1]])) {
				throw new \InvalidArgumentException(sprintf('Format "%s" is unknown in time format', $match[1]));
			}

			$replace=$translation_table[$match[1]];

			if (is_string($replace)) {
				return $timestamp->format($replace);
			} else {
				return $replace($timestamp, $match[1]);
			}
		}, $format);

		$out=str_replace('%%', '%', $out);

		return $out;
	}

}

?>