<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 *
 */

/* config-middle oswframe.cache-stable */
osW_setVar('cache_path', '.caches/');

/* config-middle oswframe.core-stable */
osW_setVar('frame_engine_loaded', false);
osW_setVar('frame_output_loaded', false);
osW_setVar('project_protection_user', '');
osW_setVar('project_protection_password', '');
osW_setVar('frame_default_engine', 'default');
osW_setVar('frame_default_output', 'default');

/* config-middle oswframe.database-stable */
osW_setVar('database_type', 'mysql');
osW_setVar('database_server', '');
osW_setVar('database_port', 3306);
osW_setVar('database_username', '');
osW_setVar('database_password', '');
osW_setVar('database_db', '');
osW_setVar('database_prefix', 'osw_');
osW_setVar('database_engine', 'InnoDB');
osW_setVar('database_character', 'utf8mb4');
osW_setVar('database_collation', 'utf8mb4_general_ci');
osW_setVar('database_slowruntime', 0.5);

/* config-middle oswframe.debug-stable */
osW_setVar('debug_write_logs', true);
osW_setVar('debug_apachelevel', 32767);
osW_setVar('debug_lib', true);
osW_setVar('debug_lib_lvl', 99);
osW_setVar('debug_lib_max_y', 50);
osW_setVar('debug_path', '.logs/');
osW_setVar('debug_maxsize', 1048576);
osW_setVar('debug_maxdays', 30);
osW_setVar('debug_gc_probability', true);
osW_setVar('debug_gc_divisor', 100);

/* config-middle oswframe.errorlogger-stable */
osW_setVar('errorlogger_module', '_errorlogger');

/* config-middle oswframe.language-stable */
osW_setVar('language_availablelanguages', ["en_US"]);

/* config-middle oswframe.session-stable */
osW_setVar('session_enabled', true);
osW_setVar('session_path', '.sessions/');
osW_setVar('session_name', 'oswsid');
osW_setVar('session_lifetime', 1800);
osW_setVar('session_cookie_lifetime', 0);
osW_setVar('session_verifyip', 'x.x');
osW_setVar('session_verifyua', true);
osW_setVar('session_secure', false);
osW_setVar('session_httponly', false);
osW_setVar('session_historycount', 3);
osW_setVar('session_gc_probability', true);
osW_setVar('session_gc_divisor', 10);
osW_setVar('session_use_only_cookies', false);

/* config-middle oswframe.settings-stable */
osW_setVar('settings_system', 'unix');
osW_setVar('settings_port', 80);
osW_setVar('settings_ssl_port', 443);
osW_setVar('settings_ssl', true);
osW_setVar('settings_modules_runs', 5);
osW_setVar('settings_slowruntime', 0.5);
osW_setVar('settings_ramlimit', 8388608);
osW_setVar('settings_chmod_file', 0644);
osW_setVar('settings_chmod_dir', 0755);
osW_setVar('settings_protection_salt', 'KLg2%§$jkhAKJdhas3254jkhh@sdafkj');

/* config-middle oswframe.template-stable */
osW_setVar('template_stripoutput', true);
osW_setVar('template_gzipcompression', true);
osW_setVar('template_gzipcompression_level', 9);
osW_setVar('template_versionnumber', 'cachetime');

?>