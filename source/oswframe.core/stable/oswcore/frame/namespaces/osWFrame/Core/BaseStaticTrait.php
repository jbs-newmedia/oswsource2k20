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

trait BaseStaticTrait
{
    /**
     * Gibt die Version als String zurück.
     *
     */
    public static function getVersion(): string
    {
        return self::CLASS_MAJOR_VERSION . '.' . self::CLASS_MINOR_VERSION . '.' . self::CLASS_RELEASE_VERSION . self::CLASS_EXTRA_VERSION;
    }

    /**
     * Gibt die Version als Integer zurück.
     *
     */
    public static function getVersionId(): int
    {
        return self::CLASS_MAJOR_VERSION . sprintf('%02d', self::CLASS_MINOR_VERSION) . sprintf(
            '%02d',
            self::CLASS_RELEASE_VERSION
        );
    }

    /**
     * Liefert den Klassennamen ohne Namespace.
     *
     */
    public static function getClassName(string $namespace = '', string $class = ''): string
    {
        if ($namespace === '') {
            $namespace = __NAMESPACE__;
        }
        if ($class === '') {
            $class = __CLASS__;
        }

        return str_replace($namespace . '\\', '', $class);
    }

    /**
     * Liefert den Klassennamen mit Namespace als String mit _.
     *
     */
    public static function getNameAsString(string $class = ''): string
    {
        if ($class === '') {
            $class = __CLASS__;
        }

        return str_replace('\\', '_', $class);
    }
}
