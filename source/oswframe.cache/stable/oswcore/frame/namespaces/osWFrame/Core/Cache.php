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

class Cache
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
     * Liefert den Dateinamen.
     *
     */
    public static function getFileName(string $module, string $file, string $extension = '.cache'): string
    {
        return Settings::getStringVar('settings_abspath') . Settings::getStringVar(
            'cache_path'
        ) . 'files' . \DIRECTORY_SEPARATOR . strtolower($module) . \DIRECTORY_SEPARATOR . strtolower($file . $extension);
    }

    /**
     * Liefert den Verzeichnisnamen.
     *
     */
    public static function getDirName(string $module): string
    {
        return Settings::getStringVar('settings_abspath') . Settings::getStringVar(
            'cache_path'
        ) . 'files' . \DIRECTORY_SEPARATOR . strtolower($module) . \DIRECTORY_SEPARATOR;
    }

    /**
     * Schreibt den Cache.
     *
     */
    public static function writeCache(string $module, string $file, string $data, string $extension = '.cache'): bool
    {
        $dirname = self::getDirName($module);
        Filesystem::makeDir($dirname);
        $filename = self::getFileName($module, $file, $extension);
        if (file_put_contents($filename, $data) !== false) {
            Filesystem::changeFilemode($filename);

            return true;
        }

        return false;
    }

    /**
     * Schreibt den Cache in einen durch htaccess geschützten Ordner.
     *
     */
    public static function writeProtectedCache(
        string $module,
        string $file,
        string $data,
        string $extension = '.cache'
    ): bool {
        $result = self::writeCache($module, $file, $data, $extension);
        Filesystem::protectDir(self::getDirName($module));

        return $result;
    }

    /**
     * Schreibt einen Array als Cache.
     *
     */
    public static function writeCacheArray(string $module, string $file, array $data): bool
    {
        return self::writeCache($module, $file, serialize($data));
    }

    /**
     * Schreibt einen Array als Cache in einen durch htaccess geschützten Ordner.
     *
     */
    public static function writeProtectedCacheArray(string $module, string $file, array $data): bool
    {
        return self::writeProtectedCache($module, $file, serialize($data));
    }

    /**
     * Gibt einen Cache als Typ Bool zurück.
     * Existiert der Cache nicht, wird NULL zurückgeliefert.
     *
     */
    public static function readCacheAsBool(
        string $module,
        string $file,
        int $expire = 0,
        string $extension = '.cache'
    ): ?bool {
        $result = self::readCache($module, $file, $expire, $extension);
        if ($result !== null) {
            return (bool)$result;
        }

        return null;
    }

    /**
     * Gibt einen Cache als Typ String zurück.
     * Existiert der Cache nicht, wird NULL zurückgeliefert.
     *
     */
    public static function readCacheAsString(
        string $module,
        string $file,
        int $expire = 0,
        string $extension = '.cache'
    ): ?string {
        $result = self::readCache($module, $file, $expire, $extension);
        if ($result !== null) {
            return (string)$result;
        }

        return null;
    }

    /**
     * Gibt einen Cache als Typ Int zurück.
     * Existiert der Cache nicht, wird NULL zurückgeliefert.
     *
     */
    public static function readCacheAsInt(
        string $module,
        string $file,
        int $expire = 0,
        string $extension = '.cache'
    ): ?int {
        $result = self::readCache($module, $file, $expire, $extension);
        if ($result !== null) {
            return (int)$result;
        }

        return null;
    }

    /**
     * Gibt einen Cache als Typ Float zurück.
     * Existiert der Cache nicht, wird NULL zurückgeliefert.
     *
     */
    public static function readCacheAsFloat(
        string $module,
        string $file,
        int $expire = 0,
        string $extension = '.cache'
    ): ?float {
        $result = self::readCache($module, $file, $expire, $extension);
        if ($result !== null) {
            return (float)$result;
        }

        return null;
    }

    /**
     * Gibt einen Cache als Typ Array zurück.
     * Existiert der Cache nicht, wird NULL zurückgeliefert.
     *
     */
    public static function readCacheAsArray(
        string $module,
        string $file,
        int $expire = 0,
        string $extension = '.cache'
    ): ?array {
        $result = self::readCache($module, $file, $expire, $extension);
        if ($result !== null) {
            return unserialize($result);
        }

        return null;


        return unserialize($result);
    }

    /**
     * Löscht einen Cache.
     *
     */
    public static function deleteCache(string $module, string $file, string $extension = '.cache'): bool
    {
        $filename = self::getFileName($module, $file, $extension);
        if (file_exists($filename)) {
            if (Filesystem::unlink($filename) === true) {
                return true;
            }

            return false;
        }

        return true;
    }

    /**
     * Prüft ob es den Cache gibt.
     *
     */
    public static function existsCache(string $module, string $file, string $extension = '.cache'): bool
    {
        $filename = self::getFileName($module, $file, $extension);
        if (file_exists($filename)) {
            return true;
        }

        return false;
    }

    /**
     * Gibt den Aktualisierungszeitpunkt des Caches zurück.
     *
     */
    public static function getCacheModTime(string $module, string $file, string $extension = '.cache'): ?int
    {
        $filename = self::getFileName($module, $file, $extension);
        if (self::existsCache($module, $file, $extension) === true) {
            return Filesystem::getFileModTime($filename);
        }

        return null;
    }

    /**
     * Gibt die Größe des Caches in Bytes zurück.
     *
     */
    public function size(string $module, string $file, string $extension = '.cache'): ?int
    {
        $filename = self::getFileName($module, $file, $extension);
        if (self::existsCache($module, $file, $extension) === true) {
            return filesize($filename);
        }

        return null;
    }

    /**
     * Leert den Cache.
     * TODO: Testen
     *
     */
    public static function clearCache(string $module, int $expire, string $extension = '.cache'): bool
    {
        $dir = self::getDirName($module);
        $oldertime = time() - $expire;
        $dir_a = scandir($dir);
        foreach ($dir_a as $filename) {
            if (($filename !== '.') && ($filename !== '..')) {
                $name = str_replace($extension, '', $filename);
                if (($filename !== $name) && (Filesystem::getFileModTime($dir . $filename) < $oldertime)) {
                    Filesystem::unlink($dir . $filename);
                }
            }
        }

        return true;
    }

    /**
     * Gibt einen Cache zurück.
     * Existiert der Cache nicht, wird NULL zurückgeliefert.
     *
     */
    protected static function readCache(
        string $module,
        string $file,
        int $expire = 0,
        string $extension = '.cache'
    ): ?string {
        $filename = self::getFileName($module, $file, $extension);
        if (file_exists($filename)) {
            if (($expire === 0) || (filemtime($filename) >= (time() - $expire))) {
                return file_get_contents($filename);
            }
        }

        return null;
    }
}
