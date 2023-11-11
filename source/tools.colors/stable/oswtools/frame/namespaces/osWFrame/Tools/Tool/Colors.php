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

class Colors extends CoreTool
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
    protected array $colors = [];

    /**
     */
    protected string $color = '';

    /**
     *
     */
    public function __construct(string $serverlist, string $package, string $release)
    {
        parent::__construct($serverlist, $package, $release);
        $this->colors = ['hex' => 'HEX', 'rgb' => 'RGB'];
    }

    /**
     */
    public function getColors(): array
    {
        return $this->colors;
    }

    /**
     * @return $this
     */
    public function setColor(string $color): self
    {
        if (!isset($this->colors[$color])) {
            $this->color = 'hex';
        } else {
            $this->color = $color;
        }

        return $this;
    }

    /**
     */
    public function getColor(): string
    {
        return $this->color;
    }
}
