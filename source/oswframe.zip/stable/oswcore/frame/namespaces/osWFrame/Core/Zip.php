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

class Zip extends \ZipArchive
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

    protected ?\ZipArchive $Zip = null;

    protected string $file = '';

    public function __construct(
        string $file
    ) {
        $this->file = $file;
        $this->Zip = new \ZipArchive();
    }

    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @return mixed
     */
    public function openFile(int $flags = 0)
    {
        return $this->open($this->getFile(), $flags);
    }

    /**
     * @param string $file
     */
    public function packDir(string $dir): bool
    {
        if ($this->openFile(\ZipArchive::CREATE) === true) {
            $this->packDirEngine($dir);
            $this->close();

            return true;
        }

        return false;
    }

    public function packDirEngine(string $dir): bool
    {
        $handle = opendir($dir);
        while ($fp = readdir($handle)) {
            if (($fp !== '.') && ($fp !== '..')) {
                $file = $dir . $fp;
                if (is_dir($file)) {
                    $this->addEmptyDir(str_replace($dir, '', $file));
                    $this->packDirEngine($file . \DIRECTORY_SEPARATOR);
                }
                if (is_file($file)) {
                    $this->addFile($file, str_replace($dir, '', $file));
                }
            }
        }
        closedir($handle);

        return true;
    }

    public function unpackDir(string $dir, int $chmod_dir = 0, int $chmod_file = 0): bool
    {
        if ($chmod_dir === 0) {
            $chmod_dir = Settings::getIntVar('settings_chmod_dir');
        }
        if ($chmod_file === 0) {
            $chmod_file = Settings::getIntVar('settings_chmod_file');
        }
        $this->openFile();
        if ($this->count() > 0) {
            if (Filesystem::isDir($dir) !== true) {
                Filesystem::makeDir($dir, $chmod_dir);
            }
            Filesystem::changeDirmode($dir, $chmod_dir);
            for ($i = 0; $i < $this->count(); $i++) {
                $stat = $this->statIndex($i);
                if (($stat['crc'] === 0) && ($stat['size'] === 0)) {
                    if (Filesystem::isDir($dir . $stat['name']) !== true) {
                        Filesystem::makeDir($dir . $stat['name'], $chmod_dir);
                    }
                    Filesystem::changeDirmode($dir . $stat['name'], $chmod_dir);
                } else {
                    $data = $this->getFromIndex($i);
                    file_put_contents($dir . $stat['name'], $data);
                    Filesystem::changeFilemode($dir . $stat['name'], $chmod_file);
                }
            }

            return true;
        }

        return false;
    }
}
