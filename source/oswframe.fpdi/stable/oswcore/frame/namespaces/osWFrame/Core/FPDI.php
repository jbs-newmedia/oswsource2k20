<?php declare(strict_types=0);

/**
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package   osWFrame
 * @link      https://oswframe.com
 * @license   MIT License
 */

namespace osWFrame\Core;

class FPDI extends \setasign\Fpdi\Tcpdf\Fpdi
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
     * @return $this
     */
    public function Header(): self
    {
        $this->SetFont('freesans', 'B', 20);
        $this->SetTextColor(0);
        $this->SetXY(PDF_MARGIN_LEFT, 5);
        $this->Cell(0, 10, 'TCPDF and FPDI');

        return $this;
    }

    /**
     * @return $this
     */
    public function Footer(): self
    {
        // empty method body
        return $this;
    }
}
