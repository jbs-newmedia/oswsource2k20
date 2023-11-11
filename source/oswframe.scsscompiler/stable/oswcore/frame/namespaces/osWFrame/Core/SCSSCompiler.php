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

use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\OutputStyle;

class SCSSCompiler
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

    protected ?Compiler $Compiler = null;

    /**
     *
     * @param array|null $cacheOptions
     */
    public function __construct(
        $cacheOptions = null
    ) {
        $this->Compiler = new Compiler($cacheOptions);
    }

    public function getCompressed(string $content): string
    {
        $this->Compiler->setOutputStyle(OutputStyle::COMPRESSED);

        return $this->Compiler->compileString($content)->getCss();
    }

    public function getExpanded(string $content): string
    {
        $this->Compiler->setOutputStyle(OutputStyle::EXPANDED);

        return $this->Compiler->compileString($content)->getCss();
    }
}
