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

use osWFrame\Core\Settings;

/**
 * Setzt eine Variable vom Typ Bool, String, Int, Float oder Array.
 *
 */
function osW_setVar(string $name, string|int|float|bool|null|array $value): bool
{
    switch (gettype($value)) {
        case 'bool':
        case 'boolean':
            if (Settings::setBoolVar($name, $value) === true) {
                return true;
            }

            break;
        case 'int':
        case 'integer':
            if (Settings::setIntVar($name, $value) === true) {
                return true;
            }

            break;
        case 'array':
            if (Settings::setArrayVar($name, $value) === true) {
                return true;
            }

            break;
        case 'float':
        case 'double':
            if (Settings::setFloatVar($name, $value) === true) {
                return true;
            }

            break;
        case 'NULL':
            return false;
        case 'string':
        default:
            if (Settings::setStringVar($name, $value) === true) {
                return true;
            }

            break;
    }

    return false;
}

/**
 * Gibt eine Variable vom Typ Bool, String, Int, Float oder Array zurück.
 * Existiert die Variable nicht, wird NULL zurückgeliefert.
 *
 */
function osW_getVar(string $name): string|int|float|bool|null|array
{
    switch (Settings::getVarType($name)) {
        case 'bool':
            return Settings::getBoolVar($name);

            break;
        case 'string':
            return Settings::getStringVar($name);

            break;
        case 'int':
            return Settings::getIntVar($name);

            break;
        case 'float':
            return Settings::getFloatVar($name);

            break;
        case 'array':
            return Settings::getArrayVar($name);

            break;
        default:
            return null;

            break;
    }
}

/**
 */
function osW_vOut(string $name): string|int|float|bool|null|array
{
    return osW_getVar($name);
}

/**
 */
function osW_catchVar(
    string $key,
    string $default = '',
    string $order = 'gpc',
    ?int $index = null
): string|int|float|bool|null|array {
    return Settings::catchValue($key, $default, $order, $index);
}

/**
 *
 */
function osW_dieScript(string $content): void
{
    Settings::dieScript($content);
}
