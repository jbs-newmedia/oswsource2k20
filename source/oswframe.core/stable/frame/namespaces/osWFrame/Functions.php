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

/**
 * Setzt eine Variable vom Typ Bool, String, Int, Float oder Array.
 *
 * @param string $name
 * @param mixed $value
 * @return bool
 */
function osW_setVar(string $name, $value):bool {
	switch (gettype($value)) {
		case 'bool':
			if (\osWFrame\Core\Settings::setBoolVar($name, $value)===true) {
				return true;
			}
			break;
		case 'integer':
			if (\osWFrame\Core\Settings::setIntVar($name, $value)===true) {
				return true;
			}
			break;
		case 'array':
			if (\osWFrame\Core\Settings::setArrayVar($name, $value)===true) {
				return true;
			}
			break;
		case 'double':
			if (\osWFrame\Core\Settings::setFloatVar($name, $value)===true) {
				return true;
			}
			break;
		case 'NULL':
			return false;
		case 'string':
		default:
			if (\osWFrame\Core\Settings::setStringVar($name, $value)===true) {
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
 * @param string $name
 * @return mixed
 */
function osW_getVar(string $name) {
	switch (\osWFrame\Core\Settings::getVarType($name)) {
		case 'bool':
			osWFrame\Core\Settings::getBoolVar($name);
			break;
		case 'string':
			osWFrame\Core\Settings::getStringVar($name);
			break;
		case 'int':
			osWFrame\Core\Settings::getIntVar($name);
			break;
		case 'float':
			osWFrame\Core\Settings::getFloatVar($name);
			break;
		case 'array':
			osWFrame\Core\Settings::getArrayVar($name);
			break;
		default:
			return null;
			break;
	}
}

/**
 *
 * @param string $name
 * @return mixed|null
 */
function osW_vOut(string $name) {
	return osW_getVar($name);
}

/**
 *
 * @param string $key
 * @param string $default
 * @param string $order
 * @param int|null $index
 * @return mixed
 */
function osW_catchVar(string $key, string $default='', string $order='gpc', ?int $index=null) {
	return osWFrame\Core\Settings::catchValue($key, $default, $order, $index);
}

/**
 *
 * @param string $content
 * @return string
 */
function osW_dieScript(string $content):string {
	return osWFrame\Core\Settings::dieScript($content);
}

?>