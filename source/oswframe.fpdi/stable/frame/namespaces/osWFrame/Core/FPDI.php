<?php

/**
 *
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package osWFrame
 * @link https://oswframe.com
 * @license MIT License
 */

namespace osWFrame\Core;

#$file=Settings::getStringVar('settings_abspath').'vendor'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.(string)Settings::getStringVar('vendor_class_tcpdf_version').DIRECTORY_SEPARATOR.'tcpdf.php';
#if ((file_exists($file))&&(class_exists('TCPDF')!==true)) {
#require_once $file;
#}

class FPDI extends \setasign\Fpdi\Tcpdf\Fpdi {

	use BaseStaticTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=1;

	/**
	 * Minor-Version der Klasse.
	 */
	private const CLASS_MINOR_VERSION=2;

	/**
	 * Release-Version der Klasse.
	 */
	private const CLASS_RELEASE_VERSION=0;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	/**
	 * @return object
	 */
	public function Header():object {
		if ($this->tplId===null) {
		}

		$this->SetFont('freesans', 'B', 20);
		$this->SetTextColor(0);
		$this->SetXY(PDF_MARGIN_LEFT, 5);
		$this->Cell(0, 10, 'TCPDF and FPDI');

		return $this;
	}

	/**
	 * @return object
	 */
	public function Footer():object {
		// empty method body
		return $this;
	}

}

?>