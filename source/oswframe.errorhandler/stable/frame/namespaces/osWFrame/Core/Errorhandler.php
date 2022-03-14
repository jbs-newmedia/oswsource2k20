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

class Errorhandler {

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
	 * Errorhandler constructor.
	 */
	private function __construct() {

	}

	/**
	 * Setzt den Errorhandler des Frames.
	 *
	 * @return bool
	 */
	public static function setHandler():bool {
		set_error_handler('osWFrame\Core\Errorhandler::handleError');

		return true;
	}

	/**
	 * Loggt die Fehler.
	 *
	 * @param int $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param int $errline
	 * @return bool
	 */
	public static function handleError(int $errno, string $errstr, string $errfile, int $errline):bool {
		switch ($errno) {
			case E_ERROR :
			case E_USER_ERROR :
				MessageStack::addMessage(self::getNameAsString(), 'fatal', ['time'=>time(), 'errno'=>$errno, 'errstr'=>$errstr, 'errfile'=>$errfile, 'errline'=>$errline]);
				#Settings::dieScript('1['.$errno.'] '.$errstr.'<br/>Fatal error in line '.$errline.' of file '.$errfile);
				break;
			case E_WARNING :
			case E_USER_WARNING :
				MessageStack::addMessage(self::getNameAsString(), 'warning', ['time'=>time(), 'errno'=>$errno, 'errstr'=>$errstr, 'errfile'=>$errfile, 'errline'=>$errline]);
				break;
			case E_NOTICE :
			case E_USER_NOTICE :
				MessageStack::addMessage(self::getNameAsString(), 'notice', ['time'=>time(), 'errno'=>$errno, 'errstr'=>$errstr, 'errfile'=>$errfile, 'errline'=>$errline]);
				#Settings::dieScript('['.$errno.'] '.$errstr.'<br/>Fatal error in line '.$errline.' of file '.$errfile);
				break;
			default :
				MessageStack::addMessage(self::getNameAsString(), 'unknow', ['time'=>time(), 'errno'=>$errno, 'errstr'=>$errstr, 'errfile'=>$errfile, 'errline'=>$errline]);
				break;
		}

		return true;
	}

}

?>