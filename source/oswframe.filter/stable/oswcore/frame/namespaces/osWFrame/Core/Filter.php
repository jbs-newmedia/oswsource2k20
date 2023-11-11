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

class Filter
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

    private function __construct()
    {
    }

    public static function verifyPattern(mixed $variable, int $filter = \FILTER_DEFAULT, array|int $options = []): bool
    {
        if (filter_var($variable, $filter, $options)) {
            return true;
        }

        return false;
    }

    public static function verifyEmailIDNAPattern(string $email): bool
    {
        $parts = explode('@', $email);
        if (\count($parts) !== 2) {
            return false;
        }
        [$user, $domain] = $parts;
        if (($user === null) && ($domain === null)) {
            return false;
        }
        $domain = idn_to_ascii($domain);
        $email = $user . '@' . $domain;

        return self::verifyEmailPattern($email);
    }

    public static function verifyEmailPattern(string $email): bool
    {
        return self::verifyPattern($email, \FILTER_VALIDATE_EMAIL);
    }

    public static function verifyIPPattern(string $ip): bool
    {
        return self::verifyPattern($ip, \FILTER_VALIDATE_IP);
    }

    public static function verifyUrlIDNAPattern(string $url): bool
    {
        return self::verifyUrlPattern(idn_to_ascii($url));
    }

    public static function verifyUrlPattern(string $url): bool
    {
        return self::verifyPattern($url, \FILTER_VALIDATE_URL);
    }

    public static function verifyDomainIDNAPattern(string $url): bool
    {
        return self::verifyUrlPattern(idn_to_ascii($url));
    }

    public static function verifyDomainPattern(string $url): bool
    {
        return self::verifyPattern($url, \FILTER_VALIDATE_DOMAIN);
    }

    public static function verifyHostnameIDNAPattern(string $url): bool
    {
        return self::verifyDomainPattern(idn_to_ascii($url));
    }

    public static function verifyHostnamePattern(string $url): bool
    {
        return self::verifyPattern($url, \FILTER_VALIDATE_DOMAIN, \FILTER_FLAG_HOSTNAME);
    }
}
