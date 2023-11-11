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

trait BaseVarTrait
{
    /**
     * Array zum Speichern der Variablen.
     *
     */
    protected ?array $vars = null;

    public function initVars(): bool
    {
        if ($this->vars === null) {
            $this->clearVars();
        }

        return true;
    }

    public function resetVars(): bool
    {
        $this->vars = null;

        return true;
    }

    public function clearVars(): bool
    {
        $this->vars = [];

        return true;
    }

    public function isVarsLoaded(): bool
    {
        if ($this->vars !== null) {
            return true;
        }

        return false;
    }

    /**
     * Setzt eine Variable vom Typ Bool.
     *
     */
    public function setBoolVar(string $name, bool $value): bool
    {
        $this->initVars();
        $this->vars[$name] = $value;

        return true;
    }

    /**
     * Setzt eine Variable vom Typ String.
     *
     */
    public function setStringVar(string $name, string $value): bool
    {
        $this->initVars();
        $this->vars[$name] = $value;

        return true;
    }

    /**
     * Setzt eine Variable vom Typ Int.
     *
     */
    public function setIntVar(string $name, int $value): bool
    {
        $this->initVars();
        $this->vars[$name] = $value;

        return true;
    }

    /**
     * Setzt eine Variable vom Typ Float.
     *
     */
    public function setFloatVar(string $name, float $value): bool
    {
        $this->initVars();
        $this->vars[$name] = $value;

        return true;
    }

    /**
     * Setzt eine Variable vom Typ Array.
     *
     */
    public function setArrayVar(string $name, array $value): bool
    {
        $this->initVars();
        $this->vars[$name] = $value;

        return true;
    }

    public function setObjectVar(string $name, object $value): bool
    {
        $this->initVars();
        $this->vars[$name] = $value;

        return true;
    }

    /**
     * Gibt eine Variable vom Typ Bool zurück.
     * Existiert die Variable nicht, wird NULL zurückgeliefert.
     *
     */
    public function getBoolVar(string $name): ?bool
    {
        if (($name !== '') && (isset($this->vars[$name]))) {
            return (bool)$this->vars[$name];
        }

        return null;
    }

    /**
     * Gibt eine Variable vom Typ String zurück.
     * Existiert die Variable nicht, wird NULL zurückgeliefert.
     *
     */
    public function getStringVar(string $name): ?string
    {
        if (($name !== '') && (isset($this->vars[$name]))) {
            return (string)$this->vars[$name];
        }

        return null;
    }

    /**
     * Gibt eine Variable vom Typ Int zurück.
     * Existiert die Variable nicht, wird NULL zurückgeliefert.
     *
     */
    public function getIntVar(string $name): ?int
    {
        if (($name !== '') && (isset($this->vars[$name]))) {
            return (int)$this->vars[$name];
        }

        return null;
    }

    /**
     * Gibt eine Variable vom Typ Float zurück.
     * Existiert die Variable nicht, wird NULL zurückgeliefert.
     *
     */
    public function getFloatVar(string $name): ?float
    {
        if (($name !== '') && (isset($this->vars[$name]))) {
            return (float)$this->vars[$name];
        }

        return null;
    }

    /**
     * Gibt eine Variable vom Typ Array zurück.
     * Existiert die Variable nicht, wird NULL zurückgeliefert.
     *
     */
    public function getArrayVar(string $name): ?array
    {
        if (($name !== '') && (isset($this->vars[$name]))) {
            return (array)$this->vars[$name];
        }

        return null;
    }

    public function getObjectVar(string $name): ?object
    {
        if (($name !== '') && (isset($this->vars[$name]))) {
            return (object)$this->vars[$name];
        }

        return null;
    }

    /**
     * Gibt den Typ einer Variablen zurück.
     * Existiert die Variable nicht, wird NULL zurückgeliefert.
     *
     */
    public function getVarType(string $name): ?string
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
