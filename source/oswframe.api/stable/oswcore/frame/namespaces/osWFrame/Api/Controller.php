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

namespace osWFrame\Api;

use osWFrame\Core as osWFrame;

class Controller
{
    use osWFrame\BaseConnectionTrait;

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

    protected string $api = '';

    protected string $section = '';

    protected string $function = '';

    public function __construct(
        string $api,
        string $section,
        string $function
    ) {
        $this->setApi($this->cleanInput($api));
        $this->setSection($this->cleanInput($section));
        $this->setFunction($this->cleanInput($function));
    }

    public function cleanInput(string $input): string
    {
        return preg_replace('/[^a-z0-9-_]/', '', strtolower($input));
    }

    public function setApi(string $api): bool
    {
        $this->api = $api;

        return true;
    }

    /**
     * @return string
     */
    public function getApi(string $default = '')
    {
        if ($this->api === '') {
            return $default;
        }

        return $this->api;
    }

    public function setSection(string $section): bool
    {
        $this->section = $section;

        return true;
    }

    /**
     * @return string
     */
    public function getSection(string $default = '')
    {
        if ($this->section === '') {
            return $default;
        }

        return $this->section;
    }

    public function setFunction(string $function): bool
    {
        $this->function = $function;

        return true;
    }

    /**
     * @return string
     */
    public function getFunction(string $default = '')
    {
        if ($this->function === '') {
            return $default;
        }

        return $this->function;
    }
}
