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

class FontAwesome6
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
        if (Resource::existsResource('fontawesome', $name) !== true) {
            Resource::copyResourcePath(
                'oswcore' . \DIRECTORY_SEPARATOR . 'oswvendor' . \DIRECTORY_SEPARATOR . 'libs' . \DIRECTORY_SEPARATOR . 'fontawesome' . \DIRECTORY_SEPARATOR . $version . \DIRECTORY_SEPARATOR,
                'fontawesome' . \DIRECTORY_SEPARATOR . $version . \DIRECTORY_SEPARATOR
            );
            $path = '/';
            if (Settings::getStringVar('project_path') !== '') {
                $path .= Settings::getStringVar('project_path') . '/';
            }
            $filename = Resource::getAbsDir(
            ) . 'fontawesome' . \DIRECTORY_SEPARATOR . $version . \DIRECTORY_SEPARATOR . 'css' . \DIRECTORY_SEPARATOR . 'all.css';
            file_put_contents(
                $filename,
                str_replace(
                    'url("../webfonts/',
                    'url("' . $path . Settings::getStringVar('resource_path') . 'fontawesome/' . $version . '/webfonts/',
                    file_get_contents($filename)
                )
            );
            $filename = Resource::getAbsDir(
            ) . 'fontawesome' . \DIRECTORY_SEPARATOR . $version . \DIRECTORY_SEPARATOR . 'css' . \DIRECTORY_SEPARATOR . 'all.min.css';
            file_put_contents(
                $filename,
                str_replace(
                    'url(../webfonts/',
                    'url(' . $path . Settings::getStringVar('resource_path') . 'fontawesome/' . $version . '/webfonts/',
                    file_get_contents($filename)
                )
            );
            Resource::writeResource('fontawesome', $name, 'time:' . time());
        }
        $path = Resource::getRelDir() . 'fontawesome' . \DIRECTORY_SEPARATOR . $version . \DIRECTORY_SEPARATOR;
        if ($min === true) {
            $cssfiles = [$path . 'css' . \DIRECTORY_SEPARATOR . 'all.min.css'];
        } else {
            $cssfiles = [$path . 'css' . \DIRECTORY_SEPARATOR . 'all.css'];
        }
        $this->addTemplateCSSFiles('head', $cssfiles);

        return $this;
    }

    /**
     * Gibt die aktuelle Version zurück.
     *
     */
    public function getCurrentVersion(): string
    {
        return (string)Settings::getStringVar('vendor_lib_fontawesome_version');
    }

    /**
     * Gibt alle verfügbaren Versionen zurück.
     *
     */
    public function getVersions(): array
    {
        if ($this->versions === []) {
            $this->versions = explode(';', (string)Settings::getStringVar('vendor_lib_fontawesome_versions'));
        }

        return $this->versions;
    }
}
