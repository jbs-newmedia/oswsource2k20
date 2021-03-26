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

class SessionMessageStack {

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
	 * SessionMessageStack constructor.
	 */
	private function __construct() {

	}

	/**
	 *
	 * @return array
	 */
	private static function loadSessionMessageStack():array {
		if (Session::isVar('messageToStack')) {
			return Session::getArrayVar('messageToStack');
		} else {
			return [];
		}
	}

	/**
	 *
	 * @param array $messageToStack
	 * @return bool
	 */
	private static function saveSessionMessageStack(array $messageToStack):bool {
		return Session::setArrayVar('messageToStack', $messageToStack);
	}

	/**
	 *
	 * @param string $class
	 * @param string $type
	 * @param array $parameter
	 * @return bool
	 */
	public static function addMessage(string $class, string $type, array $parameter):bool {
		$messageToStack=self::loadSessionMessageStack();
		$messageToStack[$class][$type][]=$parameter;

		return self::saveSessionMessageStack($messageToStack);
	}

	/**
	 * Gibt die Nachrichten zurück.
	 *
	 * @param string $class
	 * @param string $type
	 * @return array
	 */
	public static function getMessages(?string $class=null, ?string $type=null):array {
		$messageToStack=self::loadSessionMessageStack();
		if ($class!==null) {
			if (isset($messageToStack[$class])) {
				if ($type!==null) {
					if (isset($messageToStack[$class][$type])) {
						self::saveSessionMessageStack($messageToStack);

						return $messageToStack[$class][$type];
					}
				} elseif (isset($messageToStack[$class])) {
					self::saveSessionMessageStack($messageToStack);

					return $messageToStack[$class];
				}
			}

			return [];
		}
		self::saveSessionMessageStack($messageToStack);

		return $messageToStack;
	}

	/**
	 * Leert die Nachrichten.
	 *
	 * @param string $class
	 * @param string $type
	 * @return bool
	 */
	public static function clearMessages(?string $class=null, ?string $type=null):bool {
		$messageToStack=self::loadSessionMessageStack();
		if ($class!==null) {
			if (isset($messageToStack[$class])) {
				if ($type!==null) {
					if (isset($messageToStack[$class][$type])) {
						unset($messageToStack[$class][$type]);
						self::saveSessionMessageStack($messageToStack);

						return true;
					}
				} elseif (isset($messageToStack[$class])) {
					unset($messageToStack[$class]);
					self::saveSessionMessageStack($messageToStack);

					return true;
				}
			}
		} else {
			$messageToStack=[];
			self::saveSessionMessageStack($messageToStack);

			return true;
		}

		return false;
	}

}

?>