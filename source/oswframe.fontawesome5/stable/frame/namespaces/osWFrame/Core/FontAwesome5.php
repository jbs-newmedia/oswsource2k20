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
	 * Bootstrap4 Version.
	 *
	 * @var string
	 */
	private const CURRENT_RESOURCE_VERSION='5.13.0';

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
	 * FontAwesome5 constructor.
	 *
	 * Lädt Bootstrap und kümmert sich um die Resourcen.
	 *
	 * @param object $Template
	 * @param string $version
	 * @param bool $min
	 */
	public function __construct(object $Template, string $version='current', bool $min=false) {
		$this->setTemplate($Template);
		if ($version=='current') {
			$version=$this->getCurrentVersion();
		} else {
			if (!in_array($version, $this->getVersions())) {
				$version=$this->getCurrentVersion();
			}
		}
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
	}

	/**
	 * Initialisiert die Klasse.
	 *
	 * @return bool
	 */
	public function init():bool {
		$this->loaded_plugins=[];
		$this->clearTemplateFiles();

		return true;
	}

	/**
	 * Gibt die aktuelle Version zurück.
	 *
	 * @return string
	 */
	public function getCurrentVersion() {
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