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

class Math
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

    /**
     * Ein Alias von radomInt.
     *
     * @param int $min Linker Rand
     * @param int $max Rechter Rand
     *
     * @return int Zufallswert
     */
    public static function rand(int $min, int $max): int
    {
        return self::randomInt($min, $max);
    }

    /**
     * Gibt einen ganzzahligen Zufallswert zurück.
     *
     * @param int $min Linker Rand
     * @param int $max Rechter Rand
     *
     * @return int Zufallswert
     */
    public static function randomInt(int $min, int $max): int
    {
        return mt_rand($min, $max);
    }

    /**
     * Gibt einen Zufallswert zurück.
     *
     * @param int $min Linker Rand
     * @param int $max Rechter Rand
     *
     * @return int Zufallswert
     */
    public static function randomFloat($min = 0, $max = 1): float
    {
        return $min + mt_rand() / self::getrandmax() * ($max - $min);
    }

    /**
     * https://www.php.net/manual/de/function.floatval.php#114486
     *
     */
    public static function isFloat($num): float
    {
        $dotPos = strrpos($num, '.');
        $commaPos = strrpos($num, ',');
        $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos : ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);

        if (!$sep) {
            return (float)(preg_replace('/[^0-9]/', '', $num));
        }

        return (float)(preg_replace('/[^0-9]/', '', substr($num, 0, $sep)) . '.' . preg_replace(
            '/[^0-9]/',
            '',
            substr($num, $sep + 1, \strlen($num))
        ));
    }

    public static function formatNumber(
        float $var,
        ?int $decimals = null,
        ?string $dec_point = null,
        ?string $thousands_sep = null
    ): string {
        $locale = localeconv();
        if ($decimals === null) {
            $decimals = $locale['frac_digits'];
        }
        if ($dec_point === null) {
            $dec_point = $locale['decimal_point'];
        }
        if ($thousands_sep === null) {
            $thousands_sep = $locale['thousands_sep'];
        }

        return number_format((float)$var, $decimals, $dec_point, $thousands_sep);
    }

    /**
     * Zeigt den größtmöglichen Zufallswert an.
     *
     */
    protected static function getrandmax(): int
    {
        return mt_getrandmax();
    }
}
