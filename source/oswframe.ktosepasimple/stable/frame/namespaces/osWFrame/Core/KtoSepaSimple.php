<?php

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license MIT License
 */

namespace osWFrame\Core;

$file=Settings::getStringVar('settings_abspath').'frame'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'KtoSepaSimple'.DIRECTORY_SEPARATOR.'KtoSepaSimple.php';
if ((file_exists($file))&&(class_exists('KtoSepaSimple')!==true)) {
	require_once $file;
}

class KtoSepaSimple extends \KtoSepaSimple {

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

	/**
	 * KtoSepaSimple constructor.
	 *
	 * @param string $FVersion
	 * @param array $FPmtInf
	 * @param int $FAnzahl
	 * @param float $FSumme
	 */
	public function __construct(string $FVersion='3', array $FPmtInf=[], int $FAnzahl=0, float $FSumme=0.00) {
		parent::__construct($FVersion, $FPmtInf, $FAnzahl, $FSumme);
	}

}

?>