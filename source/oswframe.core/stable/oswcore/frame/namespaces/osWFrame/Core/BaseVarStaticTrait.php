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

trait BaseVarStaticTrait
{
    /**
     * Array zum Speichern der Variablen.
     *
     */
    protected static ?array $vars = null;

    public static function initVars(): bool
    {
        if (self::$vars === null) {
            self::clearVars();
        }

        return true;
    }

    public static function resetVars(): bool
    {
        self::$vars = null;

        return true;
    }

    public static function clearVars(): bool
    {
        self::$vars = [];

        return true;
    }

    public static function isVarsLoaded(): bool
    {
        if (self::$vars !== null) {
            return true;
        }

        return false;
    }

    /**
     * Setzt eine Variable vom Typ Bool.
     *
     */
    public static function setBoolVar(string $name, bool $value): bool
    {
        self::initVars();
        self::$vars[$name] = $value;

        return true;
    }

    /**
     * Setzt eine Variable vom Typ String.
     *
     */
    public static function setStringVar(string $name, string $value): bool
    {
        self::initVars();
        self::$vars[$name] = $value;

        return true;
    }

    /**
     * *Setzt eine Variable vom Typ Int.
     *
     */
    public static function setIntVar(string $name, int $value): bool
    {
        self::initVars();
        self::$vars[$name] = $value;

        return true;
    }

    /**
     * *Setzt eine Variable vom Typ Float.
     *
     */
    public static function setFloatVar(string $name, float $value): bool
    {
        self::initVars();
        self::$vars[$name] = $value;

        return true;
    }

    /**
     * *Setzt eine Variable vom Typ Array.
     *
     */
    public static function setArrayVar(string $name, array $value): bool
    {
        self::initVars();
        self::$vars[$name] = $value;

        return true;
    }

    /**
     * Gibt eine Variable vom Typ Bool zurück.
     * Existiert die Variable nicht, wird NULL zurückgeliefert.
     *
     */
    public static function getBoolVar(string $name): ?bool
    {
        if (($name !== '') && (isset(self::$vars[$name]))) {
            return (bool)(self::$vars[$name]);
        }

        return null;
    }

    /**
     * Gibt eine Variable vom Typ String zurück.
     * Existiert die Variable nicht, wird NULL zurückgeliefert.
     *
     */
    public static function getStringVar(string $name): ?string
    {
        if (($name !== '') && (isset(self::$vars[$name]))) {
            return (string)(self::$vars[$name]);
        }

        return null;
    }

    /**
     * Gibt eine Variable vom Typ Int zurück.
     * Existiert die Variable nicht, wird NULL zurückgeliefert.
     *
     */
    public static function getIntVar(string $name): ?int
    {
        if (($name !== '') && (isset(self::$vars[$name]))) {
            return (int)(self::$vars[$name]);
        }

        return null;
    }

    /**
     * Gibt eine Variable vom Typ Float zurück.
     * Existiert die Variable nicht, wird NULL zurückgeliefert.
     *
     */
    public static function getFloatVar(string $name): ?float
    {
        if (($name !== '') && (isset(self::$vars[$name]))) {
            return (float)(self::$vars[$name]);
        }

        return null;
    }

    /**
     * Gibt eine Variable vom Typ Array zurück.
     * Existiert die Variable nicht, wird NULL zurückgeliefert.
     *
     */
    public static function getArrayVar(string $name): ?array
    {
        if (($name !== '') && (isset(self::$vars[$name])) && (\is_array(self::$vars[$name]))) {
            return self::$vars[$name];
        }

        return null;
    }

    /**
     * Gibt den Typ einer Variablen zurück.
     * Existiert die Variable nicht, wird NULL zurückgeliefert.
     *
     */
    public static function getVarType(string $name): ?string
    {
        switch (\gettype($name)) {
            case 'bool':
                return 'bool';

                break;
            case 'integer':
                return 'int';

                break;
            case 'array':
                return 'array';

                break;
            case 'double':
                return 'float';

                break;
            case 'string':
                return 'string';

                break;
            default:
                return null;

                break;
        }
    }
}
