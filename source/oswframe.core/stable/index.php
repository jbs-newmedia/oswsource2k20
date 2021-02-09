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

/**
 * PHP Version prüfen.
 */
if ((!defined(PHP_VERSION_ID))||(PHP_VERSION_ID<70400)) {
	die('This version of osWFrame requires PHP 7.4 or higher.<br/>You are currently running PHP '.phpversion().'.');
}

/**
 * Definieren des absoluten Pfads.
 */
define('OSWFRAME_CORE_ABSPATH', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);

/**
 * Autoloader für Namespaces einbinden.
 */
require_once OSWFRAME_CORE_ABSPATH.'frame'.DIRECTORY_SEPARATOR.'namespaces'.DIRECTORY_SEPARATOR.'osWFrame'.DIRECTORY_SEPARATOR.'Autoload.php';

/**
 * Funktionen einbinden.
 */
require_once OSWFRAME_CORE_ABSPATH.'frame'.DIRECTORY_SEPARATOR.'namespaces'.DIRECTORY_SEPARATOR.'osWFrame'.DIRECTORY_SEPARATOR.'Functions.php';

/**
 * Errorhandler wird überschrieben.
 */
\osWFrame\Core\Errorhandler::setHandler();

/**
 * Shutdownhanlder wird erstellt.
 */
\osWFrame\Core\Shutdownhandler::setHandler();

/**
 * scriptload Start setzen, wird mit $_SERVER['REQUEST_TIME_FLOAT'] intern überschrieben und ein neuer Breakpoint gesetzt.
 */
\osWFrame\Core\Debug::startTimer('scriptload');

/**
 * Alle PHP-Fehler anzeigen lassen.
 */
\osWFrame\Core\Errorlogger::setPHPErrorReporting(E_ALL);

/**
 * Absoluten Pfad auch als Variable definieren.
 */
\osWFrame\Core\Settings::setStringVar('settings_abspath', OSWFRAME_CORE_ABSPATH);

/**
 * Default-Konfiguration des Frames laden, entspricht der Initialisierung.
 */
\osWFrame\Core\Settings::loadDefaultConfigure();

/**
 * Projekt-Konfiguration des Frames laden, entspricht den Einstellungen über osWTools:Configure. Fehler wenn es nicht konfiguriert wurde.
 */
if (\osWFrame\Core\Settings::loadConfigure('modules', 'project')!==true) {
	die('osWFrame is currently not configured.');
}

/**
 * Patch-Konfiguration des Frames laden, entspricht den Einstellungen eine Projekts.
 * Wird nur über Entwickler gesetzt.
 */
\osWFrame\Core\Settings::loadConfigure('modules', 'patch');

/**
 * Alle PHP-Fehler anzeigen lassen.
 */
\osWFrame\Core\Debug::loadDebugLib();

/**
 * PHP-Fehler-Reporting wird über Einstellungen definiert.
 */
\osWFrame\Core\Errorlogger::setPHPErrorReporting(\osWFrame\Core\Settings::getIntVar('debug_apachelevel'));

/**
 * Locales setzen.
 */
\osWFrame\Core\Settings::setLocale();

/**
 * Timezone setzen.
 */
\osWFrame\Core\Settings::setTimezone();

/**
 * Default-Module setzen.
 */
\osWFrame\Core\Settings::setStringVar('frame_default_module', \osWFrame\Core\Navigation::getModuleByName(\osWFrame\Core\Settings::catchValue('module', \osWFrame\Core\Settings::getStringVar('project_default_module'), 'g')));

/**
 * Action definieren.
 */
\osWFrame\Core\Settings::setAction(\osWFrame\Core\Settings::catchValue('action', '', 'pg'));

/**
 * Projektumgebung einstellen.
 */
\osWFrame\Core\Settings::setProjectEnvironment();

/**
 * Hook für Header bei Projekt.
 */
$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('project_default_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'header.inc.php';
if (file_exists($file)) {
	require_once $file;
}

/**
 * Hook für Header bei Module.
 */
$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_default_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'header.inc.php';
if (file_exists($file)) {
	require_once $file;
}

if (\osWFrame\Core\Settings::getBoolVar('session_enabled')===true) {
	/**
	 * Sessionumgebung einstellen.
	 */
	\osWFrame\Core\Session::setEnvironment();

	/**
	 * Session überprüfen
	 */
	\osWFrame\Core\Session::checkSession();
}

/**
 * Seitenschutz über htaccess.
 */
if ((\osWFrame\Core\Settings::getStringVar('project_protection_user')!='')&&(\osWFrame\Core\Settings::getStringVar('project_protection_password')!='')) {
	if (((!isset($_SERVER['PHP_AUTH_USER']))||($_SERVER['PHP_AUTH_USER']!=\osWFrame\Core\Settings::getStringVar('project_protection_user')))||((!isset($_SERVER['PHP_AUTH_PW']))||($_SERVER['PHP_AUTH_PW']!=\osWFrame\Core\Settings::getStringVar('project_protection_password')))) {
		if ((isset($_SERVER['PHP_AUTH_USER']))&&(isset($_SERVER['PHP_AUTH_PW']))) {
			if (($_SERVER['PHP_AUTH_USER']!=\osWFrame\Core\Settings::getStringVar('project_protection_user'))||($_SERVER['PHP_AUTH_PW']!=\osWFrame\Core\Settings::getStringVar('project_protection_password'))) {
				header('WWW-Authenticate: Basic realm="'.\osWFrame\Core\HTML::outputString(\osWFrame\Core\Settings::getStringVar('project_name')).'"');
				header('HTTP/1.0 401 Unauthorized');
				osWFrame\Core\Settings::dieScript('<br/><br/><div style="text-align: center;"><h1>Zugriff verweigert!</h1></div>');
			}
		} else {
			header('WWW-Authenticate: Basic realm="'.\osWFrame\Core\HTML::outputString(\osWFrame\Core\Settings::getStringVar('project_name')).'"');
			header('HTTP/1.0 401 Unauthorized');
			osWFrame\Core\Settings::dieScript('<br/><br/><div style="text-align: center;"><h1>Zugriff verweigert!</h1></div>');
		}
	}
}

/**
 * Engine ausführen bei Projekt sofern noch keine Engine ausgeführt wurde.
 */
if ((\osWFrame\Core\Settings::getBoolVar('frame_engine_loaded')!==true)&&(\osWFrame\Core\Settings::getStringVar('project_default_engine')!==null)) {
	$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'frame'.DIRECTORY_SEPARATOR.'engines'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('project_default_engine').'.inc.php';
	if (file_exists($file)) {
		\osWFrame\Core\Settings::setBoolVar('frame_engine_loaded', true);
		require_once $file;
	}
}

/**
 * Engine ausführen bei Frame sofern noch keine Engine ausgeführt wurde.
 */
if ((\osWFrame\Core\Settings::getBoolVar('frame_engine_loaded')!==true)&&(\osWFrame\Core\Settings::getStringVar('frame_default_engine')!==null)) {
	$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'frame'.DIRECTORY_SEPARATOR.'engines'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_default_engine').'.inc.php';
	if (file_exists($file)) {
		\osWFrame\Core\Settings::setBoolVar('frame_engine_loaded', true);
		require_once $file;
	}
}

/**
 * Hook für Footer bei Projekt.
 */
$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('project_default_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'footer.inc.php';
if (file_exists($file)) {
	require_once $file;
}

/**
 * Hook für Footer bei Module.
 */
$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'footer.inc.php';
if (file_exists($file)) {
	require_once $file;
}

/**
 * scriptload Ende setzen.
 */
\osWFrame\Core\Debug::stopTimer('scriptload');

/**
 * Langsame Scripte loggen
 */
\osWFrame\Core\Settings::checkSlowRunTime();

/**
 * Output ausführen bei Projekt sofern noch keine Output ausgeführt wurde.
 */
if ((\osWFrame\Core\Settings::getBoolVar('frame_output_loaded')!==true)&&(\osWFrame\Core\Settings::getStringVar('project_default_output')!==null)) {
	$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'frame'.DIRECTORY_SEPARATOR.'outputs'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('project_default_output').'.inc.php';
	if (file_exists($file)) {
		\osWFrame\Core\Settings::setBoolVar('frame_output_loaded', true);
		require_once $file;
	}
}

/**
 * Output ausführen bei Frame sofern noch keine Output ausgeführt wurde.
 */
if ((\osWFrame\Core\Settings::getBoolVar('frame_output_loaded')!==true)&&(\osWFrame\Core\Settings::getStringVar('frame_default_output')!==null)) {
	$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'frame'.DIRECTORY_SEPARATOR.'outputs'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_default_output').'.inc.php';
	if (file_exists($file)) {
		\osWFrame\Core\Settings::setBoolVar('frame_output_loaded', true);
		require_once $file;
	}
}

/**
 * Script wird beendet.
 */
\osWFrame\Core\Settings::dieScript();

/**
 * Dieser Bereich wird nicht erreicht.
 */

?>