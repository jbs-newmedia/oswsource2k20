<?php

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

namespace osWFrame\Core;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QRCodeStatic {

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
	 * QRCode constructor.
	 *
	 * @param $data
	 * @param $options
	 */
	private function __construct() {

	}

	/**
	 * @param string $data
	 * @return void
	 */
	public static function outputSVG(string $data) {
		$gzip=true;

		$options=new QROptions(['version'=>4, 'outputType'=>QRCode::OUTPUT_MARKUP_SVG, 'imageBase64'=>false, 'eccLevel'=>QRCode::ECC_L, 'addQuietzone'=>true, 'cssClass'=>'my-css-class', 'svgOpacity'=>1.0, 'svgDefs'=>'
		<linearGradient id="g2">
			<stop offset="0%" stop-color="'.Settings::getStringVar('project_theme_color').'" />
			<stop offset="100%" stop-color="'.Settings::getStringVar('project_theme_color').'" />
		</linearGradient>
		<linearGradient id="g1">
			<stop offset="0%" stop-color="'.Settings::getStringVar('project_theme_color').'" />
			<stop offset="100%" stop-color="'.Settings::getStringVar('project_theme_color').'" />
		</linearGradient>
		<style>rect{shape-rendering:crispEdges}</style>', 'moduleValues'=>[// finder
			1536=>'url(#g1)', // dark (true)
			6=>'#fff', // light (false)
			// alignment
			2560=>'url(#g1)', 10=>'#fff', // timing
			3072=>'url(#g1)', 12=>'#fff', // format
			3584=>'url(#g1)', 14=>'#fff', // version
			4096=>'url(#g1)', 16=>'#fff', // data
			1024=>'url(#g2)', 4=>'#fff', // darkmodule
			512=>'url(#g1)', // separator
			8=>'#fff', // quietzone
			18=>'#fff',],]);

		$qrcode=(new QRCode($options))->render($data);

		header('Content-type: image/svg+xml');

		if ($gzip===true) {
			header('Vary: Accept-Encoding');
			header('Content-Encoding: gzip');
			$qrcode=gzencode($qrcode, 9);
		}
		echo $qrcode;
		Settings::dieScript();
	}

}

?>