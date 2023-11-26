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

class Bootstrap5
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
     * @var array
     */
    protected $loaded_plugins = [];

    /**
     * Speichert alle verfügbaren Versionen.
     *
     * @var array
     */
    protected $versions = [];

    /**
     * @var string
     */
    protected $version = '';

    /**
     * @var string
     */
    protected $theme = '';

    /**
     * @var bool
     */
    protected $min = true;

    /**
     * @var array
     */
    protected $custom = [];

    public function __construct(
        ?object $Template
    ) {
        if ($Template !== null) {
            $this->setTemplate($Template);
        }
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
    public function setTheme(string $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function getTheme(): string
    {
        return $this->theme;
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
    public function load(bool $load_js = true, bool $load_css = true): self
    {
        $version = $this->getVersion();
        $theme = strtolower($this->getTheme());
        $min = $this->getMin();

        $path = Settings::getStringVar(
            'settings_abspath'
        ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'oswvendor' . \DIRECTORY_SEPARATOR . 'libs' . \DIRECTORY_SEPARATOR . 'bootstrap' . \DIRECTORY_SEPARATOR . $version . \DIRECTORY_SEPARATOR;
        if ((Filesystem::existsFile(
            $path . 'css' . \DIRECTORY_SEPARATOR . 'bootstrap-' . $theme . '.css'
        ) === true) && (Filesystem::existsFile(
            $path . 'css' . \DIRECTORY_SEPARATOR . 'bootstrap-' . $theme . '.min.css'
        ) === true)
        ) {
            $theme = '-' . $theme;
        } else {
            $theme = '';
        }

        $name = $version . $theme . '.resource';
        if (Resource::existsResource('bootstrap', $name) !== true) {
            $files = [
                'js' . \DIRECTORY_SEPARATOR . 'bootstrap.bundle.js',
                'js' . \DIRECTORY_SEPARATOR . 'bootstrap.bundle.js.map',
                'js' . \DIRECTORY_SEPARATOR . 'bootstrap.bundle.min.js',
                'js' . \DIRECTORY_SEPARATOR . 'bootstrap.bundle.min.js.map',
                'css' . \DIRECTORY_SEPARATOR . 'bootstrap' . $theme . '.css',
                'css' . \DIRECTORY_SEPARATOR . 'bootstrap.css.map',
                'css' . \DIRECTORY_SEPARATOR . 'bootstrap' . $theme . '.min.css',
                'css' . \DIRECTORY_SEPARATOR . 'bootstrap.min.css.map',
            ];
            Resource::copyResourcePath(
                'oswcore' . \DIRECTORY_SEPARATOR . 'oswvendor' . \DIRECTORY_SEPARATOR . 'libs' . \DIRECTORY_SEPARATOR . 'bootstrap' . \DIRECTORY_SEPARATOR . $version . \DIRECTORY_SEPARATOR,
                'bootstrap' . \DIRECTORY_SEPARATOR . $version . $theme . \DIRECTORY_SEPARATOR,
                $files
            );
            Filesystem::renameFile(
                Settings::getStringVar('settings_abspath') . Resource::getRelDir(
                ) . 'bootstrap' . \DIRECTORY_SEPARATOR . $version . $theme . \DIRECTORY_SEPARATOR . 'css' . \DIRECTORY_SEPARATOR . 'bootstrap' . $theme . '.css',
                Settings::getStringVar('settings_abspath') . Resource::getRelDir(
                ) . 'bootstrap' . \DIRECTORY_SEPARATOR . $version . $theme . \DIRECTORY_SEPARATOR . 'css' . \DIRECTORY_SEPARATOR . 'bootstrap.css'
            );
            Filesystem::renameFile(
                Settings::getStringVar('settings_abspath') . Resource::getRelDir(
                ) . 'bootstrap' . \DIRECTORY_SEPARATOR . $version . $theme . \DIRECTORY_SEPARATOR . 'css' . \DIRECTORY_SEPARATOR . 'bootstrap' . $theme . '.min.css',
                Settings::getStringVar('settings_abspath') . Resource::getRelDir(
                ) . 'bootstrap' . \DIRECTORY_SEPARATOR . $version . $theme . \DIRECTORY_SEPARATOR . 'css' . \DIRECTORY_SEPARATOR . 'bootstrap.min.css'
            );
            Resource::writeResource('bootstrap', $name, 'time:' . time());
        }
        $path = Resource::getRelDir() . 'bootstrap' . \DIRECTORY_SEPARATOR . $version . $theme . \DIRECTORY_SEPARATOR;

        $custom = $this->getCustoms();
        ksort($custom);

        $custom_string = '';
        $custom_string_check = '';

        if ($theme === '') {
            if ($custom !== []) {
                foreach ($custom as $key => $value) {
                    $custom_string .= '$' . $key . ': ' . $value . '; ';
                }
                $custom_string_check = '-' . md5($custom_string);
            }

            if (($custom_string_check !== '') && (Filesystem::existsFile(
                Settings::getStringVar(
                    'settings_abspath'
                ) . $path . 'css' . \DIRECTORY_SEPARATOR . 'bootstrap' . $custom_string_check . '.min.css'
            ) !== true)
            ) {
                $scss = new SCSSCompiler();
                $scss->setImportPaths(
                    Settings::getStringVar(
                        'settings_abspath'
                    ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'oswvendor' . \DIRECTORY_SEPARATOR . 'libs' . \DIRECTORY_SEPARATOR . 'bootstrap' . \DIRECTORY_SEPARATOR . $this->getCurrentVersion(
                    ) . \DIRECTORY_SEPARATOR . 'scss' . \DIRECTORY_SEPARATOR
                );
                file_put_contents(
                    Settings::getStringVar(
                        'settings_abspath'
                    ) . $path . 'css' . \DIRECTORY_SEPARATOR . 'bootstrap' . $custom_string_check . '.css',
                    $scss->getExpanded($custom_string . '@import "bootstrap";')
                );
                file_put_contents(
                    Settings::getStringVar(
                        'settings_abspath'
                    ) . $path . 'css' . \DIRECTORY_SEPARATOR . 'bootstrap' . $custom_string_check . '.min.css',
                    $scss->getCompressed($custom_string . '@import "bootstrap";')
                );
            }
        }

        if ($min === true) {
            $jsfiles = [$path . 'js' . \DIRECTORY_SEPARATOR . 'bootstrap.bundle.min.js'];
            $cssfiles = [$path . 'css' . \DIRECTORY_SEPARATOR . 'bootstrap' . $custom_string_check . '.min.css'];
        } else {
            $jsfiles = [$path . 'js' . \DIRECTORY_SEPARATOR . 'bootstrap.bundle.js'];
            $cssfiles = [$path . 'css' . \DIRECTORY_SEPARATOR . 'bootstrap' . $custom_string_check . '.css'];
        }
        if ($load_js === true) {
            $this->addTemplateJSFiles('head', $jsfiles);
        }
        if ($load_css === true) {
            $this->addTemplateCSSFiles('head', $cssfiles);
        }

        return $this;
    }

    /**
     * Gibt die aktuelle Version zurück.
     *
     */
    public function getCurrentVersion(): string
    {
        return (string)Settings::getStringVar('vendor_lib_bootstrap_version');
    }

    /**
     * Gibt alle verfügbaren Versionen zurück.
     *
     */
    public function getVersions(): array
    {
        if ($this->versions === []) {
            $this->versions = explode(';', (string)Settings::getStringVar('vendor_lib_bootstrap_versions'));
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
        ) . 'oswproject' . \DIRECTORY_SEPARATOR . 'oswvendor' . \DIRECTORY_SEPARATOR . 'libs' . \DIRECTORY_SEPARATOR . 'bootstrap' . \DIRECTORY_SEPARATOR . 'plugins' . \DIRECTORY_SEPARATOR . $plugin_name . \DIRECTORY_SEPARATOR . 'loader-' . $version = Settings::getStringVar(
            'vendor_lib_bootstrap_' . $plugin_name . '_version'
        ) . '.inc.php';
        if (file_exists($loader)) {
            print_a($loader);
            include $loader;
            $this->loaded_plugins[$plugin_name] = true;

            return true;
        }
        $loader = Settings::getStringVar(
                'settings_abspath'
            ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'oswvendor' . \DIRECTORY_SEPARATOR . 'libs' . \DIRECTORY_SEPARATOR . 'bootstrap' . \DIRECTORY_SEPARATOR . 'plugins' . \DIRECTORY_SEPARATOR . $plugin_name . \DIRECTORY_SEPARATOR . 'loader-' . $version = Settings::getStringVar(
                    'vendor_lib_bootstrap_' . $plugin_name . '_version'
                ) . '.inc.php';
        if (file_exists($loader)) {
            print_a($loader);
            include $loader;
            $this->loaded_plugins[$plugin_name] = true;

            return true;
        }
        $loader = Settings::getStringVar(
            'settings_abspath'
        ) . 'oswproject' . \DIRECTORY_SEPARATOR . 'oswvendor' . \DIRECTORY_SEPARATOR . 'libs' . \DIRECTORY_SEPARATOR . 'bootstrap' . \DIRECTORY_SEPARATOR . 'plugins' . \DIRECTORY_SEPARATOR . $plugin_name . \DIRECTORY_SEPARATOR . 'loader.inc.php';
        if (file_exists($loader)) {
            include $loader;
            $this->loaded_plugins[$plugin_name] = true;

            return true;
        }
        $loader = Settings::getStringVar(
                'settings_abspath'
            ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'oswvendor' . \DIRECTORY_SEPARATOR . 'libs' . \DIRECTORY_SEPARATOR . 'bootstrap' . \DIRECTORY_SEPARATOR . 'plugins' . \DIRECTORY_SEPARATOR . $plugin_name . \DIRECTORY_SEPARATOR . 'loader.inc.php';
        if (file_exists($loader)) {
            include $loader;
            $this->loaded_plugins[$plugin_name] = true;

            return true;
        }


        return false;
    }

    /**
     * @return $this
     */
    public function setCustom(string $var, string $value): self
    {
        $this->custom[$var] = $value;

        return $this;
    }

    public function getCustom(string $var): string
    {
        if (isset($this->custom[$var])) {
            return $this->custom[$var];
        }

        return '';
    }

    public function getCustoms(): array
    {
        return $this->custom;
    }
}
