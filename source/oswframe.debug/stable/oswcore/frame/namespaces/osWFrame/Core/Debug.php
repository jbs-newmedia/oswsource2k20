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

class Debug
{
    use BaseStaticTrait;

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

    protected static array $timer = [];

    private function __construct()
    {
    }

    /**
     * Startet den Timer.
     * Bei "scriptload" als Name wird die Variable $_SERVER['REQUEST_TIME_FLOAT'] verwendet.
     *
     */
    public static function startTimer(string $name, float $microtime = 0): bool
    {
        $microtime = self::getMicrotime($microtime);
        if ($name === 'scriptload') {
            self::$timer[$name]['start'] = $_SERVER['REQUEST_TIME_FLOAT'];
            self::breakTimer($name, $microtime, 'scriptload');

            return true;
        }
        self::$timer[$name]['start'] = self::getMicrotime($microtime);

        return true;
    }

    /**
     * Setzt einen Haltepunkt im Timer.
     *
     * @param int $microtime
     */
    public static function breakTimer(string $name, float $microtime = 0, string $notice = ''): bool
    {
        $microtime = self::getMicrotime($microtime);
        self::$timer[$name]['break'][] = [
            'microtime' => $microtime,
            'notice' => $notice,
        ];

        return true;
    }

    /**
     * Stopt den Timer.
     *
     */
    public static function stopTimer(string $name, float $microtime = 0): bool
    {
        $microtime = self::getMicrotime($microtime);
        self::$timer[$name]['end'] = $microtime;

        return true;
    }

    /**
     * Berechnet die Differenz des Timers.
     *
     */
    public static function calcTimer(string $name, string $unit = 's'): float
    {
        if ((!isset(self::$timer[$name])) && (!isset(self::$timer[$name]['start'])) && (!isset(self::$timer[$name]['end']))) {
            return 0;
        }
        switch ($unit) {
            case 's':
            default:
                return self::$timer[$name]['end'] - self::$timer[$name]['start'];

                break;
        }
    }

    /**
     * Formatiert die Timerausgabe.
     *
     */
    public static function formatTimer(string $name, string $format = 'runtime: %g s', string $unit = 's'): string
    {
        return sprintf($format, self::calcTimer($name, $unit));
    }

    /**
     * Lädt die Debuglib und stellt damit print_a() zur Verfügung.
     *
     */
    public static function loadDebugLib(): bool
    {
        $file = Settings::getStringVar('settings_abspath') . 'frame' . \DIRECTORY_SEPARATOR . 'includes' . \DIRECTORY_SEPARATOR . 'debuglib.inc.php';
        $file_core = Settings::getStringVar('settings_abspath') . 'oswvendor' . \DIRECTORY_SEPARATOR . 'oswframe' . \DIRECTORY_SEPARATOR . 'debuglib.inc.php';
        if ((Settings::getBoolVar('debug_lib') === true) && ((Filesystem::existsFile($file) === true) || (Filesystem::existsFile($file_core) === true))) {
            $GLOBALS['DEBUGLIB_LVL'] = Settings::getIntVar('debug_lib_lvl');
            $GLOBALS['DEBUGLIB_MAX_Y'] = Settings::getIntVar('debug_lib_max_y');
            if (Filesystem::existsFile($file) === true) {
                require_once $file;
            } elseif (Filesystem::existsFile($file_core) === true) {
                require_once $file_core;
            }
        } else {
            $file = Settings::getStringVar('settings_abspath') . 'frame' . \DIRECTORY_SEPARATOR . 'includes' . \DIRECTORY_SEPARATOR . 'printa.inc.php';
            $file_core = Settings::getStringVar('settings_abspath') . 'oswcore' . \DIRECTORY_SEPARATOR . 'frame' . \DIRECTORY_SEPARATOR . 'includes' . \DIRECTORY_SEPARATOR . 'printa.inc.php';
            if (Filesystem::existsFile($file) === true) {
                require_once $file;
            } elseif (Filesystem::existsFile($file_core) === true) {
                require_once $file_core;
            }
        }

        return true;
    }

    public static function d(string $text, string $prefix = ''): void
    {
        $date = new \DateTimeImmutable();
        $filename = Settings::getStringVar('settings_abspath') . Settings::getStringVar(
            'debug_path'
        ) . self::getNameAsString() . \DIRECTORY_SEPARATOR . date('Ymd', time()) . '_debug.log';
        Filesystem::makeDir(
            Settings::getStringVar('settings_abspath') . Settings::getStringVar('debug_path') . self::getNameAsString(
            ) . \DIRECTORY_SEPARATOR
        );
        if ($prefix !== '') {
            $prefix .= ': ';
        }
        if (Filesystem::existsFile($filename) !== true) {
            file_put_contents($filename, $date->format('Y-m-d H:i:s') . ' - ' . $prefix . $text . "\n");
            Filesystem::changeFilemode($filename);
        } else {
            file_put_contents($filename, $date->format('Y-m-d H:i:s') . ' - ' . $prefix . $text . "\n", \FILE_APPEND);
        }
    }

    /**
     * Gibt die Microtime als Float zurück.
     *
     */
    protected static function getMicrotime(float $microtime = 0): float
    {
        if ($microtime === 0) {
            return microtime(true);
        }

        return $microtime;
    }
}
