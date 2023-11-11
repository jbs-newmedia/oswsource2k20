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
use osWFrame\Tools as Tools;

class Worker extends \osWFrame\Tools\Tool\CoreTool
{
    use Frame\BaseStaticTrait;
    use Tools\BaseSettingsTrait;

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
    private const CLASS_RELEASE_VERSION = 3;

    /**
     * Extra-Version der Klasse.
     * Zum Beispiel alpha, beta, rc1, rc2 ...
     */
    private const CLASS_EXTRA_VERSION = '';

    /**
     */
    protected array $worker_list = [];

    /**
     * @var array|string
     */
    protected string $worker = '';

    /**
     *
     */
    public function __construct(string $serverlist, string $package, string $release)
    {
        \osWFrame\Tools\Tool\CoreTool::__construct($serverlist, $package, $release);
        $this->loadWorkerList();
    }

    /**
     */
    public function getWorkerList(): array
    {
        return $this->worker_list;
    }

    /**
     */
    public function setWorker(string $worker): void
    {
        if (isset($this->worker_list[$worker])) {
            $this->worker = $worker;
        } else {
            $this->worker = '';
        }
    }

    /**
     */
    public function isWorker(): bool
    {
        if ($this->worker !== '') {
            return true;
        }

        return false;
    }

    /**
     */
    public function getWorker(): string
    {
        return $this->worker;
    }

    /**
     * @return $this
     */
    private function loadWorkerList(): self
    {
        foreach (glob(\osWFrame\Core\Settings::getStringVar('settings_abspath') . 'resources/php/worker/*.inc.php') as $file) {
            preg_match('/\$worker_title\=\'(.*)\'\;/Uis', file_get_contents($file), $worker_files);
            if (isset($worker_files[1])) {
                $worker_files = $worker_files[1];
            } else {
                $worker_files = basename($file);
            }
            $this->worker_list[basename($file)] = $worker_files;
        }

        return $this;
    }
}
