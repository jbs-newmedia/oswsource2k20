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

namespace osWFrame\Tools\Tool;

use osWFrame\Core as Frame;

class LogBrowser extends CoreTool
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
    protected array $dir_list = [];

    /**
     */
    protected array $file_list = [];

    /**
     */
    protected array $file_list_unsort = [];

    /**
     */
    protected array $file_details = [];

    /**
     *
     */
    public function __construct(string $serverlist, string $package, string $release)
    {
        parent::__construct($serverlist, $package, $release);
    }

    /**
     * @return $this
     */
    public function readLogDirs(string $dir): self
    {
        $this->dir_list = [];

        if (Frame\Filesystem::isDir($dir)) {
            $dirs = Frame\Filesystem::scanDirsToArray($dir, true, 1, true);

            foreach ($dirs as $key => $value) {
                $this->dir_list[$key] = str_replace($dir, '', $value);
            }
        }

        return $this;
    }

    /**
     */
    public function getLogDirs(): array
    {
        return $this->dir_list;
    }

    /**
     */
    public function isDir(string $dir): bool
    {
        if (\in_array($dir, $this->dir_list, true) === true) {
            return true;
        }

        return false;
    }

    /**
     */
    public function isFile(string $file): bool
    {
        if (\in_array($file, $this->file_list_unsort, true) === true) {
            return true;
        }

        return false;
    }

    /**
     * @return $this
     */
    public function readLogFiles(string $dir): self
    {
        $this->file_list = [];
        if (Frame\Filesystem::isDir($dir)) {
            $lastday = date('Ymd', time() - (60 * 60 * 24 * (int) (\osWFrame\Tools\Configure::getFrameConfigInt('debug_maxdays'))));
            $dirs = Frame\Filesystem::scanFilesToArray($dir, true, 1, true);
            foreach ($dirs as $value) {
                if (checkdate(substr(basename($value), 4, 2), substr(basename($value), 6, 2), substr(basename($value), 0, 4)) === true) {
                    $lday = (int) (substr(basename($value), 0, 8));
                    if ($lday >= $lastday) {
                        $this->file_list_unsort[] = str_replace($dir, '', $value);
                        $this->file_list[$lday][] = str_replace($dir, '', $value);
                    }
                } else {
                    $this->file_list_unsort[] = str_replace($dir, '', $value);
                    $this->file_list[0][] = str_replace($dir, '', $value);
                }
            }
            krsort($this->file_list);
        }

        return $this;
    }

    /**
     */
    public function getLogFiles(): array
    {
        return $this->file_list;
    }

    /**
     * @return $this
     */
    public function loadFile(string $dir, string $file, string $display): self
    {
        if (Frame\Filesystem::existsFile($dir . $file) === true) {
            if (substr($file, -3) === 'csv') {
                $this->file_details['type'] = 'csv';
                $lines = file($dir . $file);
                if (\count($lines) > 0) {
                    $this->file_details['head'] = explode('";"', substr(trim($lines[0]), 1, -1));
                    unset($lines[0]);
                    $lines = array_reverse($lines);
                    $this->file_details['lines'] = [];
                    foreach ($lines as $id => $line) {
                        $lines_content = explode('";"', substr(trim($line), 1, -1));
                        foreach ($lines_content as $key => $value) {
                            if ($value === '') {
                                $value = '-';
                            }
                            if ($this->file_details['head'][$key] === 'time') {
                                $this->file_details['lines'][$id][$key] = date('Y.m.d H:i:s', (int) $value);
                            } else {
                                $this->file_details['lines'][$id][$key] = str_replace('#oswbr#', "\n", $value);
                            }
                        }
                    }
                    if ($display === 'analysis') {
                        $this->analyseLines();
                    }
                } else {
                    $this->file_details['lines'] = [];
                }
            } else {
                $this->file_details['type'] = 'txt';
                $this->file_details['content'] = nl2br(file_get_contents($dir . $file));
            }
        } else {
            $this->file_details['type'] = 'err';
            $this->file_details['content'] = '&nbsp;';
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function analyseLines(): self
    {
        $unset = [];
        foreach ($this->file_details['head'] as $key => $value) {
            if ($value === 'time') {
                $unset[] = $key;
                unset($this->file_details['head'][$key]);
            }
        }
        $this->file_details['head'][-1] = 'count';
        ksort($this->file_details['head']);

        $lines = [];
        foreach ($this->file_details['lines'] as $line) {
            if ($unset !== []) {
                foreach ($unset as $_key) {
                    unset($line[$_key]);
                }
            }
            $md5 = md5(serialize($line));
            if (!isset($lines[$md5])) {
                $lines[$md5] = $line;
                $lines[$md5][-1] = 0;
                ksort($lines[$md5]);
            }
            $lines[$md5][-1]++;
        }
        uasort($lines, [$this, 'compareList']);
        $this->file_details['lines'] = $lines;

        return $this;
    }

    /**
     */
    public function compareList(array $a, array $b): int
    {
        return $a[-1] < $b[-1];
    }

    /**
     */
    public function getFileDetailType(): string
    {
        if (isset($this->file_details['type'])) {
            return $this->file_details['type'];
        }

        return '';
    }

    /**
     */
    public function getFileDetailHead(): array
    {
        if (isset($this->file_details['head'])) {
            return $this->file_details['head'];
        }

        return [];
    }

    /**
     */
    public function getFileDetailLines(): array
    {
        if (isset($this->file_details['lines'])) {
            return $this->file_details['lines'];
        }

        return [];
    }

    /**
     */
    public function getFileDetailContent(): string
    {
        if (isset($this->file_details['content'])) {
            return $this->file_details['content'];
        }

        return '';
    }

    /**
     * @return string[]
     */
    public function getDisplayOptions(): array
    {
        if ($this->getFileDetailType() === 'csv') {
            return ['table' => 'Show table layout', 'analysis' => 'Show analysis layout'];
        }

        return ['file' => 'Show file layout'];
    }

    /**
     */
    public static function getCurrentDisplayOption(string $type, string $curdisplay): string
    {
        if ($type === 'csv') {
            switch ($curdisplay) {
                case 'analysis':
                    return 'Show analysis layout';

                    break;
                case 'table':
                default:
                    return 'Show table layout';

                    break;
            }
        } else {
            return 'Show file layout';
        }
    }
}
