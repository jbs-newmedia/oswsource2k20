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

class Bootstrap5 {

	use BaseStaticTrait;
	use BaseTemplateBridgeTrait;

	/**
	 * Major-Version der Klasse.
	 */
	private const CLASS_MAJOR_VERSION=1;

	/**
	 * Minor-Version der Klasse.
	 */
	private const CLASS_MINOR_VERSION=4;

	/**
	 * Release-Version der Klasse.
	 */
	private const CLASS_RELEASE_VERSION=1;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	/**
	 * Bootstrap5 Version.
	 *
	 * @var string
	 */
	private const CURRENT_RESOURCE_VERSION='5.1.1';

	/**
	 * Verwaltet die geladenen Plugins.
	 *
	 * @var array
	 */
	private $loaded_plugins=[];

	/**
	 * Speichert alle verfügbaren Versionen.
	 *
	 * @var array
	 */
	private $versions=[];

	/**
	 * @var string
	 */
	private $version='';

	/**
	 * @var string
	 */
	private $theme='';

	/**
	 * @var bool
	 */
	private $min=true;

	/**
	 * @var array
	 */
	private $custom=[];

	/**
	 * Bootstrap5 constructor.
	 *
	 * @param object|null $Template
	 */
	public function __construct(?object $Template) {
		if ($Template!==null) {
			$this->setTemplate($Template);
		}
		$this->setVersion('current');
	}

	/**
	 * @param string $version
	 * @return $this
	 */
	public function setVersion(string $version):self {
		if ($version=='current') {
			$this->version=$this->getCurrentVersion();
		} else {
			if (in_array($version, $this->getVersions())) {
				$this->version=$version;
			} else {
				$this->version=$this->getCurrentVersion();
			}
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function getVersion():string {
		return $this->version;
	}

	/**
	 * @param string $theme
	 * @return $this
	 */
	public function setTheme(string $theme):self {
		$this->theme=$theme;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getTheme():string {
		return $this->theme;
	}

	/**
	 * @param bool $min
	 * @return $this
	 */
	public function setMin(bool $min):self {
		$this->min=$min;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getMin():bool {
		return $this->min;
	}

	/**
	 * @param bool $load_js
	 * @param bool $load_css
	 * @return $this
	 */
	public function load(bool $load_js=true, bool $load_css=true):self {
		$version=$this->getVersion();
		$theme=strtolower($this->getTheme());
		$min=$this->getMin();

		$path=Settings::getStringVar('settings_abspath').'frame'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'bootstrap5'.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR;
		if ((Filesystem::existsFile($path.'css'.DIRECTORY_SEPARATOR.'bootstrap-'.$theme.'.css')===true)&&(Filesystem::existsFile($path.'css'.DIRECTORY_SEPARATOR.'bootstrap-'.$theme.'.min.css')===true)) {
			$theme='-'.$theme;
		} else {
			$theme='';
		}

		$name=$version.$theme.'.resource';
		if (Resource::existsResource($this->getClassName(), $name)!==true) {
			$files=['js'.DIRECTORY_SEPARATOR.'bootstrap.bundle.js', 'js'.DIRECTORY_SEPARATOR.'bootstrap.bundle.js.map', 'js'.DIRECTORY_SEPARATOR.'bootstrap.bundle.min.js', 'js'.DIRECTORY_SEPARATOR.'bootstrap.bundle.min.js.map', 'css'.DIRECTORY_SEPARATOR.'bootstrap'.$theme.'.css', 'css'.DIRECTORY_SEPARATOR.'bootstrap.css.map', 'css'.DIRECTORY_SEPARATOR.'bootstrap'.$theme.'.min.css', 'css'.DIRECTORY_SEPARATOR.'bootstrap.min.css.map'];
			Resource::copyResourcePath('frame'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'bootstrap5'.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR, 'bootstrap5'.DIRECTORY_SEPARATOR.$version.$theme.DIRECTORY_SEPARATOR, $files);
			Filesystem::renameFile(Settings::getStringVar('settings_abspath').Resource::getRelDir().'bootstrap5'.DIRECTORY_SEPARATOR.$version.$theme.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'bootstrap'.$theme.'.css', Settings::getStringVar('settings_abspath').Resource::getRelDir().'bootstrap5'.DIRECTORY_SEPARATOR.$version.$theme.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'bootstrap.css');
			Filesystem::renameFile(Settings::getStringVar('settings_abspath').Resource::getRelDir().'bootstrap5'.DIRECTORY_SEPARATOR.$version.$theme.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'bootstrap'.$theme.'.min.css', Settings::getStringVar('settings_abspath').Resource::getRelDir().'bootstrap5'.DIRECTORY_SEPARATOR.$version.$theme.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'bootstrap.min.css');
			Resource::writeResource($this->getClassName(), $name, time());
		}
		$path=Resource::getRelDir().'bootstrap5'.DIRECTORY_SEPARATOR.$version.$theme.DIRECTORY_SEPARATOR;

		$custom=$this->getCustoms();
		ksort($custom);

		if ($theme=='') {
			$custom_string='';
			$custom_string_check='';
			if ($custom!=[]) {
				foreach ($custom as $key=>$value) {
					$custom_string.='$'.$key.': '.$value.'; ';
				}
				$custom_string_check='-'.md5($custom_string);
			}

			if (($custom_string_check!=='')&&(Filesystem::existsFile(Settings::getStringVar('settings_abspath').$path.'css'.DIRECTORY_SEPARATOR.'bootstrap'.$custom_string_check.'.min.css')!==true)) {
				$scss=new SCSSCompiler();
				$scss->setImportPaths(Settings::getStringVar('settings_abspath').'frame'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'bootstrap5'.DIRECTORY_SEPARATOR.$this->getCurrentVersion().DIRECTORY_SEPARATOR.'scss'.DIRECTORY_SEPARATOR);
				file_put_contents(Settings::getStringVar('settings_abspath').$path.'css'.DIRECTORY_SEPARATOR.'bootstrap'.$custom_string_check.'.css', $scss->getExpanded($custom_string.'@import "bootstrap";'));
				file_put_contents(Settings::getStringVar('settings_abspath').$path.'css'.DIRECTORY_SEPARATOR.'bootstrap'.$custom_string_check.'.min.css', $scss->getCompressed($custom_string.'@import "bootstrap";'));
			}
		}

		if ($min===true) {
			$jsfiles=[$path.'js'.DIRECTORY_SEPARATOR.'bootstrap.bundle.min.js'];
			$cssfiles=[$path.'css'.DIRECTORY_SEPARATOR.'bootstrap'.$custom_string_check.'.min.css'];
		} else {
			$jsfiles=[$path.'js'.DIRECTORY_SEPARATOR.'bootstrap.bundle.js'];
			$cssfiles=[$path.'css'.DIRECTORY_SEPARATOR.'bootstrap'.$custom_string_check.'.css'];
		}
		if ($load_js===true) {
			$this->addTemplateJSFiles('head', $jsfiles);
		}
		if ($load_css===true) {
			$this->addTemplateCSSFiles('head', $cssfiles);
		}

		return $this;
	}

	/**
	 * Gibt die aktuelle Version zurück.
	 *
	 * @return string
	 */
	public function getCurrentVersion():string {
		return self::CURRENT_RESOURCE_VERSION;
	}

	/**
	 * Gibt alle verfügbaren Versionen zurück.
	 *
	 * @return array
	 */
	public function getVersions():array {
		if ($this->versions==[]) {
			$this->versions=Filesystem::trimPathInArray(Settings::getStringVar('settings_abspath').'frame'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'bootstrap5'.DIRECTORY_SEPARATOR, Filesystem::scanDirsToArray(Settings::getStringVar('settings_abspath').'frame'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'bootstrap5'.DIRECTORY_SEPARATOR, false));
		}

		return $this->versions;
	}

	/**
	 * Lädt einen Plugin.
	 *
	 * @param string $plugin_name
	 * @param array $options
	 * @return bool
	 */
	public function loadPlugin(string $plugin_name, array $options=[]):bool {
		$plugin_name=strtolower($plugin_name);
		if (isset($this->loaded_plugins[$plugin_name])) {
			return true;
		}

		$loader=Settings::getStringVar('settings_abspath').'frame'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'bootstrap5'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$plugin_name.DIRECTORY_SEPARATOR.'loader.inc.php';
		if (file_exists($loader)) {
			include $loader;
			$this->loaded_plugins[$plugin_name]=true;

			return true;
		}

		return false;
	}

	/**
	 * @param string $var
	 * @param string $value
	 * @return $this
	 */
	public function setCustom(string $var, string $value):self {
		$this->custom[$var]=$value;

		return $this;
	}

	/**
	 * @param string $var
	 * @return string
	 */
	public function getCustom(string $var):string {
		if (isset($this->custom[$var])) {
			return $this->custom[$var];
		}

		return '';
	}

	/**
	 * @return array
	 */
	public function getCustoms():array {
		return $this->custom;
	}

}

?>