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

use osWFrame\Core\BaseStaticTrait;
use osWFrame\Core\Filesystem;
use osWFrame\Core\Settings;
use osWFrame\Core\Zip;
use phpDocumentor\Reflection\File;

class ZipGITManager extends Zip
{
    use BaseStaticTrait;

    /**
     * Major-Version der Klasse.
     */
    private const CLASS_MAJOR_VERSION = 1;

    /**
     * Minor-Version der Klasse.
     */
    private const CLASS_MINOR_VERSION = 3;

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
     */
    protected array $files = [];

    /**
     */
    protected array $directories = [];

    /**
     *
     */
    public function __construct(string $file)
    {
        parent::__construct($file);
    }

    /**
     */
    public function unpackGitDir(string $local_path, string $remote_path, array $old_files = [], array $old_directories = [], array $ignored_files = [], array $ignored_directories = []): bool
    {
        $dir = Settings::getStringVar('settings_framepath');
        if ($local_path !== '') {
            $dir .= $local_path . \DIRECTORY_SEPARATOR;
        }
        $gitbase = '';
        $gitpath = '';

        $chmod_dir = Settings::getIntVar('settings_chmod_dir');
        $chmod_file = Settings::getIntVar('settings_chmod_file');
        $this->openFile();
        if ($this->count() > 0) {
            $files = [];
            $directories = [];
            if (Filesystem::isDir($dir) !== true) {
                Filesystem::makeDir($dir, $chmod_dir);
            }
            Filesystem::changeDirmode($dir, $chmod_dir);
            for ($i = 0; $i < $this->count(); $i++) {
                $stat = $this->statIndex($i);
                if ($i === 0) {
                    $gitbase = $stat['name'];
                    $gitpath = $stat['name'];
                    if ($remote_path !== '') {
                        $gitpath .= $remote_path;
                    }
                }
                $name = str_replace([$gitpath . \DIRECTORY_SEPARATOR, $gitbase], ['', ''], $stat['name']);
                if (($stat['crc'] === 0) && ($stat['size'] === 0)) {
                    $status = 'directory';
                } else {
                    $status = 'file';
                }
                if ((strpos($stat['name'], $gitpath) === 0) && ($this->checkIgnored($name, $status, $ignored_directories, $ignored_files) !== true)) {
                    if (($stat['crc'] === 0) && ($stat['size'] === 0)) {
                        if ($status === 'directory') {
                            if (Filesystem::isDir($dir . $name) !== true) {
                                Filesystem::makeDir($dir . $name, $chmod_dir);
                            }
                            Filesystem::changeDirmode($dir . $name, $chmod_dir);

                            if (isset($old_directories[$name])) {
                                unset($old_directories[$name]);
                            }
                            $directories[$name] = '';
                        }
                    } elseif ($status === 'file') {
                        $data = $this->getFromIndex($i);
                        file_put_contents($dir . $name, $data);
                        Filesystem::changeFilemode($dir . $name, $chmod_file);
                        if (isset($old_files[$name])) {
                            unset($old_files[$name]);
                        }
                        $files[$name] = '';
                    }
                }
            }
            Filesystem::changeFilemodeFromBase($local_path);

            if ($old_files !== []) {
                foreach ($old_files as $file => $foo) {
                    Filesystem::unlink($dir . $file);
                }
            }
            if ($old_directories !== []) {
                krsort($old_directories);
                foreach ($old_directories as $directory => $foo) {
                    Filesystem::delEmptyDir($dir . $directory);
                }
            }

            $this->setFiles($files);
            $this->setDirectories($directories);

            return true;
        }

        return false;
    }

    /**
     */
    public function setFiles(array $files): void
    {
        $this->files = $files;
    }

    /**
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     */
    public function setDirectories(array $directories): void
    {
        $this->directories = $directories;
    }

    /**
     */
    public function getDirectories(): array
    {
        return $this->directories;
    }

    /**
     */
    protected function checkIgnored(string $name, string $status, array $ignored_dirs, array $ignored_files): bool
    {
        foreach ($ignored_dirs as $ignored_dir) {
            if (strpos($name, $ignored_dir) === 0) {
                return true;
            }
        }

        if ($status === 'file') {
            return \in_array($name, $ignored_files, true);
        }

        return false;
    }
}
