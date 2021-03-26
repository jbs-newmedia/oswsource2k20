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

class Filter {

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
	 * Filter constructor.
	 */
	private function __construct() {

	}

	/**
	 * @param mixed $variable
	 * @param int $filter
	 * @param array $options
	 * @return mixed
	 */
	public static function verifyPattern($variable, int $filter=FILTER_DEFAULT, $options=[]):bool {
		if (filter_var($variable, $filter, $options)) {
			return true;
		}

		return false;
	}

	/**
	 *
	 * @param string $email
	 * @return bool
	 */
	public static function verifyEmailIDNAPattern(string $email):bool {
		[$user, $domain]=explode('@', $email);
		if (($user===null)&&($domain===null)) {
			return false;
		}
		$domain=idn_to_ascii($domain);
		$email=$user.'@'.$domain;

		return self::verifyEmailPattern($email);
	}

	/**
	 *
	 * @param string $email
	 * @return bool
	 */
	public static function verifyEmailPattern(string $email):bool {
		return self::verifyPattern($email, FILTER_VALIDATE_EMAIL);
	}

	/**
	 *
	 * @param string $ip
	 * @return bool
	 */
	public static function verifyIPPattern(string $ip):bool {
		return self::verifyPattern($ip, FILTER_VALIDATE_IP);
	}

	/**
	 *
	 * @param string $url
	 * @return bool
	 */
	public static function verifyUrlIDNAPattern(string $url):bool {
		return self::verifyUrlPattern(idn_to_ascii($url));
	}

	/**
	 *
	 * @param string $url
	 * @return bool
	 */
	public static function verifyUrlPattern(string $url):bool {
		return self::verifyPattern($url, FILTER_VALIDATE_URL);
	}

	/**
	 *
	 * @param string $url
	 * @return bool
	 */
	public static function verifyDomainIDNAPattern(string $url):bool {
		return self::verifyUrlPattern(idn_to_ascii($url));
	}

	/**
	 *
	 * @param string $url
	 * @return bool
	 */
	public static function verifyDomainPattern(string $url):bool {
		return self::verifyPattern($url, FILTER_VALIDATE_DOMAIN);
	}

	/**
	 *
	 * @param string $url
	 * @return bool
	 */
	public static function verifyHostnameIDNAPattern(string $url):bool {
		return self::verifyDomainPattern(idn_to_ascii($url));
	}

	/**
	 *
	 * @param string $url
	 * @return bool
	 */
	public static function verifyHostnamePattern(string $url):bool {
		return self::verifyPattern($url, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME);
	}

}

?>