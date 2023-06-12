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

class JSLib {

	use BaseStaticTrait;
	use BaseTemplateBridgeTrait;

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
	 * Verwaltet die geladenen Plugins.
	 *
	 * @var array
	 */
	protected array $loaded_libs=[];

	/**
	 * JSLib constructor.
	 *
	 * @param object $Template
	 */
	public function __construct(object $Template) {
		$this->setTemplate($Template);
	}

	/**
	 * Lädt eine Lib.
	 *
	 * @param string $lib_name
	 * @param array $options
	 * @return bool
	 */
	public function load(string $lib_name, array $options=[]):bool {
		$lib_name=strtolower($lib_name);
		if (isset($this->loaded_libs[$lib_name])) {
			return true;
		}

		$loader=Settings::getStringVar('settings_abspath').'vendor'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'jslib'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$lib_name.DIRECTORY_SEPARATOR.'loader.inc.php';
		if (file_exists($loader)) {
			include $loader;
			$this->loaded_libs[$lib_name]=true;

			return true;
		}
		return false;
	}

}

?>