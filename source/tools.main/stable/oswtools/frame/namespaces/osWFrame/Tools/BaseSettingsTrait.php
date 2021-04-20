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

namespace osWFrame\Tools;

use osWFrame\Core as Frame;
use osWFrame\Tools as Tools;

trait BaseSettingsTrait {

	/**
	 * @var array|null
	 */
	private ?array $settings=null;

	/**
	 * @return object
	 */
	public function initSettings():object {
		if ($this->settings===null) {
			$this->clearSettings();
		}

		return $this;
	}

	/**
	 * @return object
	 */
	public function loadSettings():object {
		$this->initSettings();
		$file=Frame\Settings::getStringVar('settings_framepath').'oswtools'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'settings'.DIRECTORY_SEPARATOR.$this->getServerlist().'-'.$this->getPackage().'-'.$this->getRelease().'.json';
		if (Frame\Filesystem::isFile($file)) {
			$this->settings=json_decode(file_get_contents($file), true);
		}

		return $this;
	}

	/**
	 * @return object
	 */
	public function writeSettings():object {
		if ($this->settings!==null) {
			$dir=Frame\Settings::getStringVar('settings_framepath').'oswtools'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'json'.DIRECTORY_SEPARATOR.'settings'.DIRECTORY_SEPARATOR;
			if (Frame\Filesystem::isDir($dir)!==true) {
				Frame\Filesystem::makeDir($dir, Tools\Configure::getFrameConfigInt('settings_chmod_dir'));
			}
			$file=$dir.$this->getServerlist().'-'.$this->getPackage().'-'.$this->getRelease().'.json';
			file_put_contents($file, json_encode($this->settings));
			Frame\Filesystem::changeFilemode($file, Tools\Configure::getFrameConfigInt('settings_chmod_file'));
		}

		return $this;
	}

	/**
	 * @return object
	 */
	public function resetSettings():object {
		$this->settings=null;

		return $this;
	}

	/**
	 * @return object
	 */
	public function clearSettings():object {
		$this->settings=[];

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isSettingsLoaded():bool {
		if ($this->settings!==null) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param string $name
	 * @param bool $value
	 * @return object
	 */
	public function setBoolSetting(string $name, bool $value):object {
		$this->initSettings();
		$this->settings[$name]=$value;

		return $this;
	}

	/**
	 * @param string $name
	 * @param string $value
	 * @return object
	 */
	public function setStringSetting(string $name, string $value):object {
		$this->initSettings();
		$this->settings[$name]=$value;

		return $this;
	}

	/**
	 * @param string $name
	 * @param int $value
	 * @return object
	 */
	public function setIntSetting(string $name, int $value):object {
		$this->initSettings();
		$this->settings[$name]=$value;

		return $this;
	}

	/**
	 * @param string $name
	 * @param float $value
	 * @return object
	 */
	public function setFloatSetting(string $name, float $value):object {
		$this->initSettings();
		$this->settings[$name]=$value;

		return $this;
	}

	/**
	 * @param string $name
	 * @param array $value
	 * @return object
	 */
	public function setArraySetting(string $name, array $value):object {
		$this->initSettings();
		$this->settings[$name]=$value;

		return $this;
	}

	/**
	 * @param string $name
	 * @return bool|null
	 */
	public function getBoolSetting(string $name):?bool {
		if ((strlen($name)>0)&&(isset($this->settings[$name]))) {
			return $this->settings[$name];
		}

		return null;
	}

	/**
	 * @param string $name
	 * @return string|null
	 */
	public function getStringSetting(string $name):?string {
		if ((strlen($name)>0)&&(isset($this->settings[$name]))) {
			return $this->settings[$name];
		}

		return null;
	}

	/**
	 * @param string $name
	 * @return int|null
	 */
	public function getIntSetting(string $name):?int {
		if ((strlen($name)>0)&&(isset($this->settings[$name]))) {
			return $this->settings[$name];
		}

		return null;
	}

	/**
	 * @param string $name
	 * @return float|null
	 */
	public function getFloatSetting(string $name):?float {
		if ((strlen($name)>0)&&(isset($this->settings[$name]))) {
			return $this->settings[$name];
		}

		return null;
	}

	/**
	 * @param string $name
	 * @return array|null
	 */
	public function getArraySetting(string $name):?array {
		if ((strlen($name)>0)&&(isset($this->settings[$name]))) {
			return $this->settings[$name];
		}

		return null;
	}

	/**
	 * @param string $name
	 * @return string|null
	 */
	public function getSettingType(string $name):?string {
		switch (gettype($name)) {
			case 'bool':
				return 'bool';
				break;
			case 'integer':
				return 'int';
				break;
			case 'array':
				return 'array';
				break;
			case 'double':
				return 'float';
				break;
			case 'string':
				return 'string';
				break;
			default:
				return null;
				break;
		}
	}

}

?>