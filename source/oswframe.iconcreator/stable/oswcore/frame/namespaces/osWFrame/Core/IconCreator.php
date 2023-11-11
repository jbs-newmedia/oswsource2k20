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

class IconCreator extends \PHP_ICO
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

    public function __construct(
        string $file = '',
        array $sizes = []
    ) {
        if ($file === '') {
            parent::__construct(false, $sizes);
        } else {
            parent::__construct($file, $sizes);
        }
    }

    public function saveIcon($file): bool
    {
        return $this->save_ico($file);
    }

    public static function existsCache(string $file, array $sizes): bool
    {
        $filenamecache = md5($file . '#' . serialize($sizes));
        if ((Cache::existsCache(self::getClassName(), $filenamecache) !== true) || (Filesystem::getFileModTime(
            $file
        ) > Cache::getCacheModTime(self::getClassName(), $filenamecache))
        ) {
            return false;
        }

        return true;
    }

    public static function readCache(string $file, array $sizes): string
    {
        $filenamecache = md5($file . '#' . serialize($sizes));

        if (Cache::existsCache(self::getClassName(), $filenamecache) !== true) {
            return '';
        }

        return Cache::readCacheAsString(self::getClassName(), $filenamecache);
    }

    public function writeCache(string $file, array $sizes): bool
    {
        $filenamecache = md5($file . '#' . serialize($sizes));
        if (!$this->_has_requirements) {
            return false;
        }

        if (false === ($data = $this->_get_ico_data())) {
            return false;
        }

        return Cache::writeCache($this->getClassName(), $filenamecache, $data);
    }
}
