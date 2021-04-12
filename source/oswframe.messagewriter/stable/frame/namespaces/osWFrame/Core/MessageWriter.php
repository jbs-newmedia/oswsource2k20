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

class MessageWriter {

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
	private const CLASS_EXTRA_VERSION='beta';

	/**
	 * @var array
	 */
	private static array $ignore=[];

	/**
	 * MessageWriter constructor.
	 */
	private function __construct() {

	}

	/**
	 * @param string $key
	 * @param array $type
	 * @return bool
	 */
	public static function addIgnore(string $key, array $type=[]):bool {
		self::$ignore[$key]=$type;

		return true;
	}

	/**
	 * @param string $message
	 * @param string $br
	 * @return string
	 */
	public static function formatString(string $message):string {
		return str_replace(["\r", "\n"], ['', '#oswbr#'], $message);
	}

	/**
	 * @return bool
	 */
	public static function writeLogs():bool {
		self::addIgnore('osWFrame_Core_Form');
		self::addIgnore('osWFrame_Core_Session');
		self::addIgnore('osWFrame_Core_Database', ['notice']);
		if (Settings::getBoolVar('debug_write_logs')===true) {
			$debug_dir=Settings::getStringVar('settings_abspath').Settings::getStringVar('session_path');
			if (Filesystem::isDir($debug_dir)!==true) {
				Filesystem::protectDir($debug_dir);
			}
			foreach (MessageStack::getMessages() as $key=>$types) {
				if (!isset(self::$ignore[$key])||(self::$ignore[$key]!=[])) {
					if (Filesystem::isDir(Settings::getStringVar('settings_abspath').Settings::getStringVar('debug_path').$key.'/')!==true) {
						Filesystem::makeDir(Settings::getStringVar('settings_abspath').Settings::getStringVar('debug_path').$key.'/', Settings::getFrameConfigValue('settings_chmod_dir'));
					}
					foreach ($types as $type=>$messages) {
						if ((isset(self::$ignore[$key])!==true)||(!in_array($type, self::$ignore[$key]))) {
							$logfile=Settings::getStringVar('settings_abspath').Settings::getStringVar('debug_path').$key.'/'.date('Ymd', time()).'_'.$type.'.csv';
							if ((Filesystem::existsFile($logfile)===true)&&(filesize($logfile)>Settings::getStringVar('debug_maxsize'))) {
								$i=0;
								$find=false;
								while ($find==false) {
									$logfile=Settings::getStringVar('settings_abspath').Settings::getStringVar('debug_path').$key.'/'.date('Ymd', time()).'_'.$type.'-'.$i.'.csv';
									if ((Filesystem::existsFile($logfile)===true)&&(filesize($logfile)>Settings::getStringVar('debug_maxsize'))) {
										$i++;
									} else {
										$find=true;
									}
								}
							}

							if (Filesystem::existsFile($logfile)!==true) {
								$ar_header=array_flip($messages[0]);
								$csv_data='"'.implode('";"', $ar_header).'"';
								error_log($csv_data."\n", 3, $logfile);
							}
							foreach ($messages as $message) {
								foreach ($message as $key=>$value) {
									if (is_string($value)) {
										$message[$key]=self::formatString($value);
									}
								}
								$csv_data='"'.implode('";"', $message).'"';
								error_log($csv_data."\n", 3, $logfile);
							}
						}
					}
				}
			}
		}

		return true;
	}

}

?>