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

class BreadCrumb
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

    /**
     * BreadCrumb Data
     *
     */
    protected array $data = [];

    /**
     * Zähler
     *
     */
    protected int $count = 0;

    public function __construct()
    {
    }

    public function add(string $name = '', string $module = '', string $parameters = '', array $options = []): void
    {
        if (($module === '') || ($module === 'default')) {
            $module = Settings::getStringVar('project_default_module');
        }
        if ($module === 'current') {
            $module = Settings::getStringVar('frame_current_module');
        }
        $this->data[] = [
            'name' => $name,
            'module' => $module,
            'parameters' => $parameters,
            'options' => $options,
        ];
        $this->addCount();
    }

    public function clear(): void
    {
        $this->data = [];
    }

    public function removePosition($i): bool
    {
        if (isset($this->data[$i])) {
            unset($this->data[$i]);

            return true;
        }

        return false;
    }

    public function get(int $id = 0): ?array
    {
        if ($id > 0) {
            if (isset($this->data[$id])) {
                return $this->data[$id];
            }

            return null;
        }

        return $this->data;
    }

    public function getReverse(): array
    {
        $r_array = $this->data;
        krsort($r_array);

        return $r_array;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    protected function addCount(): void
    {
        $this->count++;
    }

    protected function clearCount(): void
    {
        $this->count = 0;
    }
}
