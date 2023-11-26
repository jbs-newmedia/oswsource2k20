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

class JSLib
{
    use BaseStaticTrait;
    use BaseTemplateBridgeTrait;

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

    /**
     * Verwaltet die geladenen Plugins.
     *
     */
    protected array $loaded_plugins = [];

    public function __construct(
        object $Template
    ) {
        $this->setTemplate($Template);
    }

    /**
     * Lädt eine Lib.
     *
     */
    public function load(string $lib_name, array $options = []): bool
    {
        $lib_name = strtolower($lib_name);
        if (isset($this->loaded_libs[$lib_name])) {
            return true;
        }

        $loader = Settings::getStringVar(
            'settings_abspath'
        ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'oswvendor' . \DIRECTORY_SEPARATOR . 'libs' . \DIRECTORY_SEPARATOR . 'jslib' . \DIRECTORY_SEPARATOR . 'plugins' . \DIRECTORY_SEPARATOR . $lib_name . \DIRECTORY_SEPARATOR . 'loader-' . $version = Settings::getStringVar(
            'vendor_lib_jslib_' . $lib_name . '_version'
        ) . '.inc.php';
        if (file_exists($loader)) {
            include $loader;
            $this->loaded_plugins[$lib_name] = true;

            return true;
        }
        $loader = Settings::getStringVar(
            'settings_abspath'
        ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'oswvendor' . \DIRECTORY_SEPARATOR . 'libs' . \DIRECTORY_SEPARATOR . 'jslib' . \DIRECTORY_SEPARATOR . 'plugins' . \DIRECTORY_SEPARATOR . $lib_name . \DIRECTORY_SEPARATOR . 'loader.inc.php';
        if (file_exists($loader)) {
            include $loader;
            $this->loaded_plugins[$lib_name] = true;

            return true;
        }


        return false;
    }
}
