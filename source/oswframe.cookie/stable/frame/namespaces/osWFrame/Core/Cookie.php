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

class Cookie {

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
	private const CLASS_RELEASE_VERSION=1;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	/**
	 * @var bool|null
	 */
	protected static ?bool $cookies_enabled=null;

	/**
	 * Cookie constructor.
	 */
	private function __construct() {

	}

	/**
	 * @return bool
	 */
	public static function isCookiesEnabled():bool {
		if (self::$cookies_enabled==null) {
			if ((defined('SID')===true)&&(strlen(SID)>0)) {
				self::$cookies_enabled=false;
			} else {
				self::$cookies_enabled=true;
			}
		}

		return self::$cookies_enabled;
	}

	/**
	 * @param string $name
	 * @param string $value
	 * @param int $expires
	 * @param string $path
	 * @param string $domain
	 * @param bool $secure
	 * @param bool $httponly
	 * @return bool
	 */
	public static function setCookie(string $name, string $value='', int $expires=0, string $path='', string $domain='', ?bool $secure=null, bool $httponly=false):bool {
    if(is_null($secure)){
        $secure = !empty($_SERVER['HTTPS']);
    }
		if (self::isCookiesEnabled()===true) {
			return setcookie($name, $value, $expires, $path, $domain, $secure, $httponly);
		}

		return false;
	}

}

?>