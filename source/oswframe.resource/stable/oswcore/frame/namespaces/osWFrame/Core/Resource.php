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

class Resource
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

    private function __construct()
    {
    }

    /**
     * Liefert das absolute Verzeichnis.
     *
     */
    public static function getAbsDir(): string
    {
        return Settings::getStringVar('settings_abspath') . Settings::getStringVar('resource_path');
    }

    /**
     * Liefert das relative Verzeichnis.
     *
     */
    public static function getRelDir(): string
    {
        return Settings::getStringVar('resource_path');
    }

    /**
     * Schreibt die Resource.
     *
     */
    public static function writeResource(string $module, string $file, string $data): bool
    {
        $dirname = self::getDirName($module);
        Filesystem::makeDir($dirname);
        $filename = self::getFileName($module, $file);
        if (file_put_contents($filename, $data) !== false) {
            Filesystem::changeFilemode($filename);

            return true;
        }

        return false;
    }

    /**
     * Kopiert die Resource.
     *
     */
    public static function copyResource(string $module, string $file, string $data): bool
    {
        $dirname = self::getDirName($module, $file);
        Filesystem::makeDir($dirname);
        $filename = self::getFileName($module, $file);
        if (copy($data, $filename) !== false) {
            Filesystem::changeFilemode($filename);

            return true;
        }

        return false;
    }

    /**
     * Kopiert das Resourcenverzeichnis.
     *
     */
    public static function copyResourcePath(string $source, string $dest, array $file_list = []): bool
    {
        $source = Settings::getStringVar('settings_abspath') . $source;
        if (Filesystem::isDir($source) !== true) {
            return false;
        }
        $dest = self::getDirName($dest);
        Filesystem::makeDir($dest);
        $len = mb_strlen($source);
        $return = true;
        foreach (Filesystem::scanFilesToArray($source, true) as $filename) {
            $filename_short = mb_substr($filename, $len);
            if (($file_list === []) || (\in_array($filename_short, $file_list, true))) {
                Filesystem::makeDir($dest . $filename_short);
                if (Filesystem::copyFile($source . $filename_short, $dest . $filename_short) === false) {
                    $return = false;
                }
            }
        }

        return $return;
    }

    /**
     * Löscht einen Resource.
     *
     */
    public function deleteResource(string $module, string $file): bool
    {
        $filename = $this->getFileName($module, $file);
        if (file_exists($filename)) {
            if (Filesystem::unlink($filename) === true) {
                return true;
            }

            return false;
        }

        return true;
    }

    /**
     * * Prüft ob es die Resource gibt.
     *
     */
    public static function existsResource(string $module, string $file): bool
    {
        $filename = self::getFileName($module, $file);
        if (file_exists($filename)) {
            return true;
        }

        return false;
    }

    /**
     * Leert die Resource.
     *
     */
    public static function clearResource(string $module, int $expire): bool
    {
        $dir = self::getDirName($module);
        $oldertime = time() - $expire;
        $dir_a = scandir($dir);
        foreach ($dir_a as $filename) {
            if ($filename !== '.' && $filename !== '..') {
                if (@filemtime($dir . $filename) < $oldertime) {
                    Filesystem::unlink($dir . $filename);
                }
            }
        }

        return true;
    }

    /**
     * Liefert den Dateinamen.
     *
     */
    protected static function getFileName(string $module, string $file): string
    {
        return self::getAbsDir() . strtolower($module) . \DIRECTORY_SEPARATOR . strtolower($file);
    }

    /**
     * Liefert den Verzeichnisnamen.
     *
     */
    protected static function getDirName(string $module, string $file = ''): string
    {
        if ($file !== '') {
            return self::getAbsDir() . strtolower($module) . \DIRECTORY_SEPARATOR . strtolower($file);
        }

        return self::getAbsDir() . strtolower($module);
    }

    /**
     * Gibt eine Resource zurück.
     * Existiert die Resource nicht, wird NULL zurückgeliefert.
     *
     */
    protected function readResource(string $module, string $file, int $expire = 0): ?string
    {
        $filename = $this->getFileName($module, $file);
        if (file_exists($filename)) {
            if (($expire === 0) || (filemtime($filename) >= (time() - $expire))) {
                return file_get_contents($filename);
            }
        }

        return null;
    }
}
