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

class Configure
{
    use Frame\BaseStaticTrait;

    /**
     * Major-Version der Klasse.
     */
    private const CLASS_MAJOR_VERSION = 1;

    /**
     * Minor-Version der Klasse.
     */
    private const CLASS_MINOR_VERSION = 0;

    /**
     * Release-Version der Klasse.
     */
    private const CLASS_RELEASE_VERSION = 1;

    /**
     * Extra-Version der Klasse.
     * Zum Beispiel alpha, beta, rc1, rc2 ...
     */
    private const CLASS_EXTRA_VERSION = '';

    /**
     */
    protected static ?array $configuration = null;

    /**
     */
    private function __construct()
    {
    }

    /**
     */
    public static function loadFrameConfig(): bool
    {
        if (self::$configuration === null) {
            self::$configuration = [];
            $configure_files = [\osWFrame\Core\Settings::getStringVar('settings_framepath') . 'oswcore' . \DIRECTORY_SEPARATOR . 'frame' . \DIRECTORY_SEPARATOR . 'configure.php', \osWFrame\Core\Settings::getStringVar('settings_framepath') . 'frame' . \DIRECTORY_SEPARATOR . 'configure.php', \osWFrame\Core\Settings::getStringVar('settings_framepath') . 'modules' . \DIRECTORY_SEPARATOR . 'configure.project.php', \osWFrame\Core\Settings::getStringVar('settings_framepath') . 'modules' . \DIRECTORY_SEPARATOR . 'configure.project-dev.php'];

            foreach ($configure_files as $configure_file) {
                if (Frame\Filesystem::existsFile($configure_file)) {
                    $content = file_get_contents($configure_file);
                    $content = str_replace('settings_abspath', 'settings_framepath', $content);
                    $content = str_replace('osW_setVar(', 'self::setFrameConfig(', $content);
                    $content = str_replace('osW_getVar(', 'self::getFrameConfigString(', $content);
                    eval(substr($content, 5));
                }
            }

            if (isset(self::$configuration['project_path']) && (self::$configuration['project_path'] !== '')) {
                self::$configuration['project_url_path'] = '/' . self::$configuration['project_path'] . '/';
            } else {
                self::$configuration['project_url_path'] = '/';
            }
        }

        return true;
    }

    /**
     */
    public static function setFrameConfig(string $var, $value): bool
    {
        self::$configuration[$var] = $value;

        return true;
    }

    /**
     */
    public static function getFrameConfigString(string $var): string
    {
        self::loadFrameConfig();
        if (isset(self::$configuration[$var])) {
            return (string) (self::$configuration[$var]);
        }

        return '';
    }

    /**
     */
    public static function getFrameConfigInt(string $var): int
    {
        self::loadFrameConfig();
        if (isset(self::$configuration[$var])) {
            return self::$configuration[$var];
        }

        return 0;
    }

    /**
     */
    public static function getFrameConfigBool(string $var): bool
    {
        self::loadFrameConfig();
        if (isset(self::$configuration[$var])) {
            return self::$configuration[$var];
        }

        return false;
    }

    /**
     */
    public static function getFrameConfig(): array
    {
        self::loadFrameConfig();

        return self::$configuration;
    }
}
