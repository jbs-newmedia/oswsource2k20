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

class GFontDownloader {

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
	private const CLASS_RELEASE_VERSION=1;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='beta';

	/**
	 * @var string
	 */
	private string $api_key='';

	/**
	 * @var array
	 */
	protected array $fonts=[];

	/**
	 * @var array
	 */
	protected array $fonts_select=[];

	/**
	 * GFontDownloader constructor.
	 *
	 * @param string $file
	 * @param object $Template
	 */
	public function __construct(string $api_key) {
		$this->setApiKey($api_key);
	}

	/**
	 * @param string $api_key
	 * @return $this
	 */
	public function setApiKey(string $api_key):self {
		$this->api_key=$api_key;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getApiKey():string {
		return $this->api_key;
	}

	/**
	 * @return $this
	 */
	public function loadFonts():self {
		$this->fonts=[];
		$cache=Cache::readCacheAsString('gfontdownloader', 'font_list', 60*60*24);
		if (null===($cache=Cache::readCacheAsString('gfontdownloader', 'font_list', 60*60*24))) {
			$cache=file_get_contents('https://www.googleapis.com/webfonts/v1/webfonts?key='.$this->getApiKey());
			Cache::writeCache('gfontdownloader', 'font_list', $cache);
		}
		$json=json_decode($cache, true);
		if ((isset($json['kind']))&&(isset($json['items']))) {
			foreach ($json['items'] as $font) {
				$font['id']=str_replace([' '], [''], strtolower($font['family']));
				$this->fonts[$font['id']]=$font;
				$this->fonts_select[$font['id']]=$font['family'];
			}
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function getFonts():array {
		return $this->fonts;
	}

	/**
	 * @return array
	 */
	public function getFontsSelect():array {
		return $this->fonts_select;
	}

	/**
	 * @param string $font
	 * @return bool
	 */
	public function installFont(string $font):bool {
		if (isset($this->fonts[$font])) {
			$variants=[];
			foreach ($this->fonts[$font]['variants'] as $variant) {
				if ((!strstr($variant, 'italic'))&&(!strstr($variant, 'bold'))) {
					$variants[]=$variant;
				}
			}

			return $this->downloadFont($this->fonts[$font]['id'], $this->fonts[$font]['family'], $variants);
		}

		return false;
	}

	/**
	 * @param string $id
	 * @param string $font
	 * @param array $variants
	 * @return bool
	 */
	public function downloadFont(string $id, string $font, array $variants):bool {
		$opts=['http'=>['method'=>"GET", 'header'=>"Accept-language: en\r\n"."Cookie: foo=bar\r\n"."User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36\r\n"]];
		$context=stream_context_create($opts);
		$css=file_get_contents('https://fonts.googleapis.com/css?family='.str_replace(' ', '+', $font).':'.implode(',', $variants), false, $context);
		$urls=[];
		preg_match_all('/url\(([a-zA-Z0-9\:\/\.-_].+)\)/Uis', $css, $urls);
		if ((isset($urls[1]))&&($urls[1]!==[])) {
			$dir=Settings::getStringVar('settings_abspath').'data'.DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR;
			if (Filesystem::isDir($dir)!==true) {
				Filesystem::makeDir($dir);
				Filesystem::changeDirmode($dir);
				$path=$urls[1][0];
				$path=str_replace(basename($path), '', $path);
				file_put_contents($dir.'font.css', str_replace($path, '/data/fonts/'.$id.'/', $css));
				Filesystem::changeFilemode($dir.'font.css');
				foreach ($urls[1] as $file) {
					file_put_contents($dir.basename($file), file_get_contents($file));
					Filesystem::changeFilemode($dir.basename($file));
				}
				file_put_contents(Settings::getStringVar('settings_abspath').'data'.DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR.$id.'.resource', time());
				Filesystem::changeFilemode(Settings::getStringVar('settings_abspath').'data'.DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR.$id.'.resource');
			}

			return true;
		}

		return false;
	}

}

?>