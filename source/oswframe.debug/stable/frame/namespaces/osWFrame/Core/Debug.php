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

class Debug {

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
	private const CLASS_RELEASE_VERSION=2;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	/**
	 * @var array
	 */
	protected static array $timer=[];

	/**
	 * Debug constructor.
	 */
	private function __construct() {

	}

	/**
	 * Gibt die Microtime als Float zurück.
	 *
	 * @param float $microtime
	 * @return float
	 */
	protected static function getMicrotime(float $microtime=0):float {
		if ($microtime==0) {
			return microtime(true);
		}

		return $microtime;
	}

	/**
	 * Startet den Timer.
	 * Bei "scriptload" als Name wird die Variable $_SERVER['REQUEST_TIME_FLOAT'] verwendet.
	 *
	 * @param string $name
	 * @param float $microtime
	 * @return bool
	 */
	public static function startTimer(string $name, float $microtime=0):bool {
		$microtime=self::getMicrotime($microtime);
		if ($name=='scriptload') {
			self::$timer[$name]['start']=$_SERVER['REQUEST_TIME_FLOAT'];
			self::breakTimer($name, $microtime, 'scriptload');

			return true;
		}
		self::$timer[$name]['start']=self::getMicrotime($microtime);

		return true;
	}

	/**
	 * Setzt einen Haltepunkt im Timer.
	 *
	 * @param string $name
	 * @param int $microtime
	 * @param string $notice
	 * @return bool
	 */
	public static function breakTimer(string $name, float $microtime=0, string $notice=''):bool {
		$microtime=self::getMicrotime($microtime);
		self::$timer[$name]['break'][]=['microtime'=>$microtime, 'notice'=>$notice];

		return true;
	}

	/**
	 * Stopt den Timer.
	 *
	 * @param string $name
	 * @param float $microtime
	 * @return bool
	 */
	public static function stopTimer(string $name, float $microtime=0):bool {
		$microtime=self::getMicrotime($microtime);
		self::$timer[$name]['end']=$microtime;

		return true;
	}

	/**
	 * Berechnet die Differenz des Timers.
	 *
	 * @param string $name
	 * @param string $unit
	 * @return float
	 */
	public static function calcTimer(string $name, string $unit='s'):float {
		if ((!isset(self::$timer[$name]))&&(!isset(self::$timer[$name]['start']))&&(!isset(self::$timer[$name]['end']))) {
			return 0;
		}
		switch ($unit) {
			case 's':
			default:
				return self::$timer[$name]['end']-self::$timer[$name]['start'];
				break;
		}
	}

	/**
	 * Formatiert die Timerausgabe.
	 *
	 * @param string $name
	 * @param string $format
	 * @param string $unit
	 * @return string
	 */
	public static function formatTimer(string $name, string $format='runtime: %g s', string $unit='s'):string {
		return sprintf($format, self::calcTimer($name, $unit));
	}

	/**
	 * Lädt die Debuglib und stellt damit print_a() zur Verfügung.
	 *
	 * @return bool
	 */
	public static function loadDebugLib():bool {
		if (Settings::getBoolVar('debug_lib')===true) {
			$GLOBALS['DEBUGLIB_LVL']=Settings::getIntVar('debug_lib_lvl');
			$GLOBALS['DEBUGLIB_MAX_Y']=Settings::getIntVar('debug_lib_max_y');
			require_once Settings::getStringVar('settings_abspath').'frame'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'debuglib.inc.php';
		} else {
			require_once Settings::getStringVar('settings_abspath').'frame'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'printa.inc.php';
		}

		return true;
	}

}