<?php declare(strict_types=0);

/**
 * This file is part of the osWFrame package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   osWFrame
 * @link      https://oswframe.com
 * @license   MIT License
 */

namespace osWFrame\Core;

class Cookie
{
    use BaseStaticTrait;

    /**
     * Major-Version der Klasse.
     */
    private const CLASS_MAJOR_VERSION = 2;

    /**
     * Minor-Version der Klasse.
     */
    private const CLASS_MINOR_VERSION = 0;

    /**
     * Release-Version der Klasse.
     */
    private const CLASS_RELEASE_VERSION = 0;

    /**
     * Extra-Version der Klasse.
     * Zum Beispiel alpha, beta, rc1, rc2 ...
     */
    private const CLASS_EXTRA_VERSION = '';

    protected static ?bool $cookies_enabled = null;

    private function __construct()
    {
    }

    public static function isCookiesEnabled(): bool
    {
        if (self::$cookies_enabled === null) {
            if ((\defined('SID') === true) && (SID !== '')) {
                self::$cookies_enabled = false;
            } else {
                self::$cookies_enabled = true;
            }
        }

        return self::$cookies_enabled;
    }

    public static function setCookie(
        string $name,
        string $value = '',
        int $expires = 0,
        string $path = '',
        string $domain = '',
        bool $secure = false,
        bool $httponly = false
    ): bool {
        if (self::isCookiesEnabled() === true) {
            return setcookie($name, $value, $expires, $path, $domain, $secure, $httponly);
        }

        return false;
    }

    public static function deleteCookie(
        string $name,
        string $path = '',
        string $domain = '',
        bool $secure = false,
        bool $httponly = false
    ): bool {
        if (self::isCookiesEnabled() === true) {
            return self::setCookie($name, '', time() - 3600, $path, $domain, $secure, $httponly);
        }

        return false;
    }
}
