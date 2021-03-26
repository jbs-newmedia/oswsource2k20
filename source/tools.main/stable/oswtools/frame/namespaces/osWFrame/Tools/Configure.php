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

class Configure {

	use Frame\BaseStaticTrait;

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
	private const CLASS_EXTRA_VERSION='beta';

	/**
	 * @var array|null
	 */
	private static ?array $configuration=null;

	/**
	 * Helper constructor.
	 */
	private function __construct() {

	}

	/**
	 * @return bool
	 */
	public static function loadFrameConfig():bool {
		if (self::$configuration==null) {
			self::$configuration=[];
			$configure_files=[\osWFrame\Core\Settings::getStringVar('settings_framepath').'frame'.DIRECTORY_SEPARATOR.'configure.php', \osWFrame\Core\Settings::getStringVar('settings_framepath').'modules'.DIRECTORY_SEPARATOR.'configure.project.php', \osWFrame\Core\Settings::getStringVar('settings_framepath').'modules'.DIRECTORY_SEPARATOR.'configure.project-dev.php'];

			foreach ($configure_files as $configure_file) {
				if (Frame\Filesystem::existsFile($configure_file)) {
					$content=file_get_contents($configure_file);
					$content=str_replace('settings_abspath', 'settings_framepath', $content);
					$content=str_replace('osW_setVar(', 'self::setFrameConfig(', $content);
					$content=str_replace('osW_getVar(', 'self::getFrameConfig(', $content);
					eval(substr($content, 5));
				}
			}
		}

		return true;
	}

	/**
	 * @param string $var
	 * @param $value
	 * @return bool
	 */
	public static function setFrameConfig(string $var, $value):bool {
		self::$configuration[$var]=$value;

		return true;
	}

	/**
	 * @param string $var
	 * @param string $type
	 * @return mixed
	 */
	public static function getFrameConfig(string $var, string $type='string') {
		self::loadFrameConfig();
		if (isset(self::$configuration[$var])) {
			return self::$configuration[$var];
		}

		return '';
	}

}

?>