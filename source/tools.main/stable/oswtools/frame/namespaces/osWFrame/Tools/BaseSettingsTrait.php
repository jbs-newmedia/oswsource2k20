<?php declare(strict_types=0);

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license MIT License
 */

namespace osWFrame\Tools;

use osWFrame\Core as Frame;
use osWFrame\Tools as Tools;

trait BaseSettingsTrait
{
    /**
     */
    protected ?array $settings = null;

    /**
     * @return $this
     */
    public function initSettings(): self
    {
        if ($this->settings === null) {
            $this->clearSettings();
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function loadSettings(): self
    {
        $this->initSettings();
        $file = Frame\Settings::getStringVar('settings_framepath') . 'oswtools' . \DIRECTORY_SEPARATOR . 'resources' . \DIRECTORY_SEPARATOR . 'json' . \DIRECTORY_SEPARATOR . 'settings' . \DIRECTORY_SEPARATOR . $this->getServerlist() . '-' . $this->getPackage() . '-' . $this->getRelease() . '.json';
        if (Frame\Filesystem::isFile($file)) {
            $this->settings = json_decode(file_get_contents($file), true);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function writeSettings(): self
    {
        if ($this->settings !== null) {
            $dir = Frame\Settings::getStringVar('settings_framepath') . 'oswtools' . \DIRECTORY_SEPARATOR . 'resources' . \DIRECTORY_SEPARATOR . 'json' . \DIRECTORY_SEPARATOR . 'settings' . \DIRECTORY_SEPARATOR;
            if (Frame\Filesystem::isDir($dir) !== true) {
                Frame\Filesystem::makeDir($dir, Tools\Configure::getFrameConfigInt('settings_chmod_dir'));
            }
            $file = $dir . $this->getServerlist() . '-' . $this->getPackage() . '-' . $this->getRelease() . '.json';
            file_put_contents($file, json_encode($this->settings));
            Frame\Filesystem::changeFilemode($file, Tools\Configure::getFrameConfigInt('settings_chmod_file'));
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function resetSettings(): self
    {
        $this->settings = null;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearSettings(): self
    {
        $this->settings = [];

        return $this;
    }

    /**
     */
    public function isSettingsLoaded(): bool
    {
        if ($this->settings !== null) {
            return true;
        }

        return false;
    }

    /**
     * @return $this
     */
    public function setBoolSetting(string $name, bool $value): self
    {
        $this->initSettings();
        $this->settings[$name] = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function setStringSetting(string $name, string $value): self
    {
        $this->initSettings();
        $this->settings[$name] = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function setIntSetting(string $name, int $value): self
    {
        $this->initSettings();
        $this->settings[$name] = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function setFloatSetting(string $name, float $value): self
    {
        $this->initSettings();
        $this->settings[$name] = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function setArraySetting(string $name, array $value): self
    {
        $this->initSettings();
        $this->settings[$name] = $value;

        return $this;
    }

    /**
     */
    public function getBoolSetting(string $name): ?bool
    {
        if (($name !== '') && (isset($this->settings[$name]))) {
            return $this->settings[$name];
        }

        return null;
    }

    /**
     */
    public function getStringSetting(string $name): ?string
    {
        if (($name !== '') && (isset($this->settings[$name]))) {
            return $this->settings[$name];
        }

        return null;
    }

    /**
     */
    public function getIntSetting(string $name): ?int
    {
        if (($name !== '') && (isset($this->settings[$name]))) {
            return $this->settings[$name];
        }

        return null;
    }

    /**
     */
    public function getFloatSetting(string $name): ?float
    {
        if (($name !== '') && (isset($this->settings[$name]))) {
            return $this->settings[$name];
        }

        return null;
    }

    /**
     */
    public function getArraySetting(string $name): ?array
    {
        if (($name !== '') && (isset($this->settings[$name]))) {
            return $this->settings[$name];
        }

        return null;
    }

    /**
     */
    public function getSettingType(string $name): ?string
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
