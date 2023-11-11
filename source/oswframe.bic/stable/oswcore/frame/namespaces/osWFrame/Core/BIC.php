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

class BIC
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

    protected string $bic = '';

    public function __construct(
        string $bic = ''
    ) {
        $this->setBIC($bic);
    }

    public function setBIC(string $bic): void
    {
        $this->bic = $bic;
    }

    public function getBIC(): string
    {
        return $this->bic;
    }

    public function verify(string $bic = ''): bool
    {
        $regexp = '/^[A-Z]{6,6}[A-Z2-9][A-NP-Z0-9]([A-Z0-9]{3,3}){0,1}$/i';
        if ($bic === '') {
            return (bool)(preg_match($regexp, $this->getBIC()));
        }

        return (bool)(preg_match($regexp, $bic));
    }
}
