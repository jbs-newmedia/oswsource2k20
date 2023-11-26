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

class jQuery3
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

    /**
     * Speichert alle verfügbaren Versionen.
     *
     */
    protected array $versions = [];

    /**
     * @var string
     */
    protected $version = '';

    /**
     * @var bool
     */
    protected $min = true;

    public function __construct(
        object $Template
    ) {
        $this->setTemplate($Template);
        $this->setVersion('current');
    }

    /**
     * @return $this
     */
    public function setVersion(string $version): self
    {
        if ($version === 'current') {
            $this->version = $this->getCurrentVersion();
        } else {
            if (\in_array($version, $this->getVersions(), true)) {
                $this->version = $version;
            } else {
                $this->version = $this->getCurrentVersion();
            }
        }

        return $this;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return $this
     */
    public function setMin(bool $min): self
    {
        $this->min = $min;

        return $this;
    }

    public function getMin(): bool
    {
        return $this->min;
    }

    /**
     * @return $this
     */
    public function load(): self
    {
        $version = $this->getVersion();
        $min = $this->getMin();

        $name = $version . '.resource';
        if (Resource::existsResource('jquery', $name) !== true) {
            $files = [
                'js' . \DIRECTORY_SEPARATOR . 'jquery.js',
                'js' . \DIRECTORY_SEPARATOR . 'jquery.min.js',
                'js' . \DIRECTORY_SEPARATOR . 'jquery.min.map',
            ];
            Resource::copyResourcePath(
                'oswcore' . \DIRECTORY_SEPARATOR . 'oswvendor' . \DIRECTORY_SEPARATOR . 'libs' . \DIRECTORY_SEPARATOR . 'jquery' . \DIRECTORY_SEPARATOR . $version . \DIRECTORY_SEPARATOR,
                'jquery' . \DIRECTORY_SEPARATOR . $version . \DIRECTORY_SEPARATOR,
                $files
            );
            Resource::writeResource('jquery', $name, 'time:' . time());
        }
        $path = Resource::getRelDir() . 'jquery' . \DIRECTORY_SEPARATOR . $version . \DIRECTORY_SEPARATOR;
        if ($min === true) {
            $jsfiles = [$path . 'js' . \DIRECTORY_SEPARATOR . 'jquery.min.js'];
        } else {
            $jsfiles = [$path . 'js' . \DIRECTORY_SEPARATOR . 'jquery.js'];
        }
        $this->addTemplateJSFiles('head', $jsfiles);

        return $this;
    }

    /**
     * Gibt die aktuelle Version zurück.
     *
     * @return string
     */
    public function getCurrentVersion()
    {
        return (string)Settings::getStringVar('vendor_lib_jquery_version');
    }

    /**
     * Gibt alle verfügbaren Versionen zurück.
     *
     */
    public function getVersions(): array
    {
        if ($this->versions === []) {
            $this->versions = explode(';', (string)Settings::getStringVar('vendor_lib_jquery_versions'));
        }

        return $this->versions;
    }

    /**
     * Lädt einen Plugin.
     *
     */
    public function loadPlugin(string $plugin_name, array $options = []): bool
    {
        $plugin_name = strtolower($plugin_name);
        if (isset($this->loaded_plugins[$plugin_name])) {
            return true;
        }

        $loader = Settings::getStringVar(
            'settings_abspath'
        ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'oswvendor' . \DIRECTORY_SEPARATOR . 'libs' . \DIRECTORY_SEPARATOR . 'jquery' . \DIRECTORY_SEPARATOR . 'plugins' . \DIRECTORY_SEPARATOR . $plugin_name . \DIRECTORY_SEPARATOR . 'loader-' . $version = Settings::getStringVar(
            'vendor_lib_jquery_' . $plugin_name . '_version'
        ) . '.inc.php';
        if (file_exists($loader)) {
            include $loader;
            $this->loaded_plugins[$plugin_name] = true;

            return true;
        }
        $loader = Settings::getStringVar(
            'settings_abspath'
        ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'oswvendor' . \DIRECTORY_SEPARATOR . 'libs' . \DIRECTORY_SEPARATOR . 'jquery' . \DIRECTORY_SEPARATOR . 'plugins' . \DIRECTORY_SEPARATOR . $plugin_name . \DIRECTORY_SEPARATOR . 'loader.inc.php';
        if (file_exists($loader)) {
            include $loader;
            $this->loaded_plugins[$plugin_name] = true;

            return true;
        }


        return false;
    }
}
