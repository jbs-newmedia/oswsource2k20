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
	private const CLASS_RELEASE_VERSION=5;

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
	protected const CURRENT_RESOURCE_VERSION='5.15.4';

	/**
	 * Verwaltet die geladenen Plugins.
	 *
	 * @var array
	 */
	protected array $loaded_plugins=[];

	/**
	 * Speichert alle verfügbaren Versionen.
	 *
	 * @var array
	 */
	protected array $versions=[];

	/**
	 * @var string
	 */
	protected $version='';

	/**
	 * @var bool
	 */
	protected $min=true;

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
	 * @return $this
	 */
	public function load():self {
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
			file_put_contents($filename, str_replace('url("../webfonts/', 'url("'.$path.Settings::getStringVar('resource_path').'fontawesome5/'.$version.'/webfonts/', file_get_contents($filename)));
			$filename=Resource::getAbsDir().'fontawesome5'.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'all.min.css';
			file_put_contents($filename, str_replace('url(../webfonts/', 'url('.$path.Settings::getStringVar('resource_path').'fontawesome5/'.$version.'/webfonts/', file_get_contents($filename)));
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