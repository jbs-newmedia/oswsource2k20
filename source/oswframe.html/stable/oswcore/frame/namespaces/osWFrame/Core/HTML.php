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

class HTML
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
     * Entfernt alle unnötigen Zeichen aus dem HTML-Code.
     *
     */
    public static function stripContent(string $content): string
    {
        $content = str_replace("\t", ' ', $content); // tabs
        $content = str_replace("\n", ' ', $content); // returns
        $content = str_replace("\r", ' ', $content); // returns
        $content = preg_replace('/[ ]+/', ' ', $content); // double blanks
        $content = preg_replace('/<!--[^-]*-->/', '', $content); // comments

        return $content;
    }

    /**
     * Kodiert einen String zur Ausgabe als HTML.
     *
     */
    public static function outputString(string $content, bool $nl2br = true): string
    {
        if ($nl2br === true) {
            return nl2br(htmlentities($content, \ENT_COMPAT, 'UTF-8'));
        }

        return htmlentities($content, \ENT_COMPAT, 'UTF-8');
    }
}
