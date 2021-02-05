<?php

/**
 *
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package osWFrame
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

namespace osWFrame\Core;

$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'frame'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
if ((file_exists($file))&&(class_exists('TCPDF')!==true)) {
	require_once $file;
}

class FPDI extends \setasign\Fpdi\Tcpdf\Fpdi {

	use BaseStaticTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=1;

	/**
	 * Minor-Version der Klasse.
	 */
	private const CLASS_MINOR_VERSION=0;

	/**
	 * Release-Version der Klasse.
	 */
	private const CLASS_RELEASE_VERSION=0;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	function Header() {
		if ($this->tplId===null) {
		}

		$this->SetFont('freesans', 'B', 20);
		$this->SetTextColor(0);
		$this->SetXY(PDF_MARGIN_LEFT, 5);
		$this->Cell(0, $size['height'], 'TCPDF and FPDI');
	}

	function Footer() {
		// emtpy method body
	}

}

?>