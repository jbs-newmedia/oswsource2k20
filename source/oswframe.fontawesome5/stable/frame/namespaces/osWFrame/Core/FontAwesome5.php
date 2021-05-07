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

class FontAwesome5 {

	use BaseStaticTrait;
	use BaseTemplateBridgeTrait;

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
	private const CLASS_RELEASE_VERSION=1;

	/**
	 * Extra-Version der Klasse.
	 * Zum Beispiel alpha, beta, rc1, rc2 ...
	 */
	private const CLASS_EXTRA_VERSION='';

	/**
	 * Bootstrap4 Version.
	 *
	 * @var string
	 */
	private const CURRENT_RESOURCE_VERSION='5.15.3';

	/**
	 * Verwaltet die geladenen Plugins.
	 *
	 * @var array
	 */
	private array $loaded_plugins=[];

	/**
	 * Speichert alle verfügbaren Versionen.
	 *
	 * @var array
	 */
	private array $versions=[];

	/**
	 * @var string
	 */
	private $version='';

	/**
	 * @var bool
	 */
	private $min=true;

	/**
	 * FontAwesome5 constructor.
	 *
	 * @param object $Template
	 */
	public function __construct(object $Template) {
		$this->setTemplate($Template);
		$this->setVersion('current');
	}

	/**
	 * @param string $version
	 * @return object
	 */
	public function setVersion(string $version):object {
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
	 * @param string $min
	 * @return object
	 */
	public function setMin(bool $min):object {
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
	 * @return object
	 */
	public function load():object {
		$version=$this->getVersion();
		$min=$this->getMin();

		$name=$version.'.resource';
		if (Resource::existsResource($this->getClassName(), $name)!==true) {
			Resource::copyResourcePath('frame'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'fontawesome5'.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR, 'fontawesome5'.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR);
			$path='/';
			if (strlen(Settings::getStringVar('project_path'))>0) {
				$path.=Settings::getStringVar('project_path').'/';
			}
			$filename=Resource::getAbsDir().'fontawesome5'.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'all.css';
			file_put_contents($filename, str_replace('url("../webfonts/', 'url("'.$path.Settings::getStringVar('resource_path').'fontawesome5'.DIRECTORY_SEPARATOR.$version.'/webfonts/', file_get_contents($filename)));
			$filename=Resource::getAbsDir().'fontawesome5'.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'all.min.css';
			file_put_contents($filename, str_replace('url(../webfonts/', 'url('.$path.Settings::getStringVar('resource_path').'fontawesome5'.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR.'/webfonts/', file_get_contents($filename)));
			Resource::writeResource($this->getClassName(), $name, time());
		}
		$path=Resource::getRelDir().'fontawesome5'.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR;
		if ($min===true) {
			$cssfiles=[$path.'css'.DIRECTORY_SEPARATOR.'all.min.css'];
		} else {
			$cssfiles=[$path.'css'.DIRECTORY_SEPARATOR.'all.css'];
		}
		$this->addTemplateCSSFiles('head', $cssfiles);

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
			$this->versions=Filesystem::trimPathInArray(Settings::getStringVar('settings_abspath').'frame'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'bootstrap4'.DIRECTORY_SEPARATOR, Filesystem::scanDirsToArray(Settings::getStringVar('settings_abspath').'frame'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'bootstrap4'.DIRECTORY_SEPARATOR, false));
		}

		return $this->versions;
	}

}

?>