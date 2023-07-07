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
	private const CLASS_MINOR_VERSION=1;

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
	 * @param array $option
	 * @return void
	 */
	public static function outputSVG(string $data, array $option=[]) {
		$gzip=true;

		if (!isset($option['version'])) {
			$option['version']=4;
		}

		if (!isset($option['outputType'])) {
			$option['outputType']=QRCode::OUTPUT_MARKUP_SVG;
		}

		if (!isset($option['imageBase64'])) {
			$option['imageBase64']=false;
		}

		if (!isset($option['eccLevel'])) {
			$option['eccLevel']=QRCode::ECC_L;
		}

		if (!isset($option['addQuietzone'])) {
			$option['addQuietzone']=true;
		}

		if (!isset($option['cssClass'])) {
			$option['cssClass']='my-css-class';
		}

		if (!isset($option['svgOpacity'])) {
			$option['svgOpacity']=1.0;
		}

		if (!isset($option['project_theme_color_start'])) {
			$option['project_theme_color_start']='#000000';
		}

		if (!isset($option['project_theme_color_end'])) {
			$option['project_theme_color_end']='#000000';
		}

		if (!isset($option['project_theme_color_bg'])) {
			$option['project_theme_color_bg']='#ffffff';
		}

		if (!isset($option['svgDefs'])) {
			$option['svgDefs']='<linearGradient id="g2"><stop offset="0%" stop-color="'.$option['project_theme_color_start'].'" /><stop offset="100%" stop-color="'.$option['project_theme_color_end'].'" /></linearGradient><linearGradient id="g1"><stop offset="0%" stop-color="'.$option['project_theme_color_start'].'" /><stop offset="100%" stop-color="'.$option['project_theme_color_end'].'" /></linearGradient><style>rect{shape-rendering:crispEdges}</style>';
		}

		if (!isset($option['moduleValues'])) {
			$option['moduleValues']=[// finder
				1536=>'url(#g1)', // dark (true)
				6=>$option['project_theme_color_bg'], // light (false)
				// alignment
				2560=>'url(#g1)', 10=>$option['project_theme_color_bg'], // timing
				3072=>'url(#g1)', 12=>$option['project_theme_color_bg'], // format
				3584=>'url(#g1)', 14=>$option['project_theme_color_bg'], // version
				4096=>'url(#g1)', 16=>$option['project_theme_color_bg'], // data
				1024=>'url(#g2)', 4=>$option['project_theme_color_bg'], // darkmodule
				512=>'url(#g1)', // separator
				8=>$option['project_theme_color_bg'], // quietzone
				18=>$option['project_theme_color_bg']];
		}

		$options=new QROptions(['version'=>$option['version'], 'outputType'=>$option['outputType'], 'imageBase64'=>$option['imageBase64'], 'eccLevel'=>$option['eccLevel'], 'addQuietzone'=>$option['addQuietzone'], 'cssClass'=>$option['cssClass'], 'svgOpacity'=>$option['svgOpacity'], 'svgDefs'=>$option['svgDefs'], 'moduleValues'=>$option['moduleValues']]);
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