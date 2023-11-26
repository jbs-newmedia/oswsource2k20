<?php declare(strict_types=0);

/**
 * This file is part of the osWFrame package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   osWFrame
 * @link      https://oswframe.com
 * @license   MIT License
 */

namespace osWFrame\Core;

class Navigation
{
    use BaseStaticTrait;

    /**
     * Major-Version der Klasse.
     */
    private const CLASS_MAJOR_VERSION = 2;

    /**
     * Minor-Version der Klasse.
     */
    private const CLASS_MINOR_VERSION = 0;

    /**
     * Release-Version der Klasse.
     */
    private const CLASS_RELEASE_VERSION = 0;

    /**
     * Extra-Version der Klasse.
     * Zum Beispiel alpha, beta, rc1, rc2 ...
     */
    private const CLASS_EXTRA_VERSION = '';

    private function __construct()
    {
    }

    public static function getModuleByName(string $name): string
    {
        return Language::getNameModule($name);
    }

    /**
     * Baut eine URL.
     *
     */
    public static function buildUrl(
        string $module = '',
        string $get_parameters = '',
        bool $seowrite_inpage = false,
        bool $add_session = true
    ): string {
        if (($module === '') || ($module === 'default')) {
            $module = Settings::getStringVar('project_default_module');
        }
        if ($module === 'current') {
            $module = Settings::getStringVar('frame_current_module');
        }
        $base_uri = '';
        $check_parameters = true;
        $rewrite_module = true;
        if ((\defined('SID') === true) && (SID !== '')) {
            $get_parameters .= '&' . htmlspecialchars(SID);
        }
        $ar_parameters = [];
        if ($get_parameters !== '') {
            $extend = explode('#', $get_parameters);
            $ar_temp = explode('&', $extend[0]);
            foreach ($ar_temp as $value) {
                if (\strlen($value) >= 3) {
                    $temp = explode('=', $value);
                    if (($temp[0] !== '') && ($temp[1] !== '')) {
                        if (!isset($ar_parameters[$temp[0]])) {
                            $ar_parameters[$temp[0]] = $temp[1];
                        }
                    }
                }
            }
        }
        if (isset($ar_parameters['page'])) {
            if ($ar_parameters['page'] === 1) {
                unset($ar_parameters['page']);
            }
        }
        $acceptable_spider_parameters = [];
        if ($add_session === true) {
            $acceptable_user_parameters = [Settings::getStringVar('session_name')];
        } else {
            $acceptable_user_parameters = [];
        }
        $go_default = true;
        $file = Settings::getStringVar(
            'settings_abspath'
        ) . 'oswproject' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . $module . \DIRECTORY_SEPARATOR . 'rewrite' . \DIRECTORY_SEPARATOR . 'rules.inc.php';
        $file_core = Settings::getStringVar(
            'settings_abspath'
        ) . 'oswcore' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . $module . \DIRECTORY_SEPARATOR . 'rewrite' . \DIRECTORY_SEPARATOR . 'rules.inc.php';
        if (file_exists($file)) {
            include $file;
        } elseif (file_exists($file_core)) {
            include $file_core;
        }
        if ((\defined('SID') === true) && (SID === '')) {
            $id = array_search(Settings::getStringVar('session_name'), $acceptable_user_parameters, true);
            if ($id !== false) {
                unset($acceptable_user_parameters[$id]);
            }
        }
        if ($go_default === true) {
            if ($module !== Settings::getStringVar('project_default_module')) {
                if ($rewrite_module === true) {
                    $base_uri = Language::getModuleName($module);
                } else {
                    $base_uri = $module;
                }
            }
            if (Session::getIsCrawler() === true) {
                $acceptable_parameters = $acceptable_spider_parameters;
            } else {
                $acceptable_parameters = $acceptable_user_parameters;
            }
            $parameters = [];
            if ($check_parameters === true) {
                foreach ($acceptable_parameters as $parameter) {
                    if (isset($ar_parameters[$parameter])) {
                        if ($parameter === Settings::getStringVar('session_name')) {
                            if ($add_session === true) {
                                if ($ar_parameters[$parameter] === Session::getId()) {
                                    $parameters[$parameter] = $ar_parameters[$parameter];
                                } else {
                                    $parameters[$parameter] = Session::getId();
                                }
                            }
                        } else {
                            $parameters[$parameter] = $ar_parameters[$parameter];
                        }
                    }
                }
            } else {
                $parameters = $ar_parameters;
            }
            $base_uri .= '?';
            foreach ($parameters as $key => $value) {
                $base_uri .= $key . '=' . $value . '&';
            }
            $base_uri = substr($base_uri, 0, -1);
            if (isset($extend[1])) {
                $base_uri .= '#' . $extend[1];
            }
        }

        return Settings::getStringVar('project_domain_full') . $base_uri;
    }

    public static function getCurrentUrl(): string
    {
        return Network::getCurrentUrl();
    }

    public static function getCanonicalUrl(): string
    {
        $query_string = '';
        if (isset($_SERVER['QUERY_STRING'])) {
            $query_string = $_SERVER['QUERY_STRING'];
        }

        return self::buildUrl('current', $query_string, true, false);
    }

    public static function getCleanUrl(): string
    {
        $query_string = '';
        if (isset($_SERVER['QUERY_STRING'])) {
            $query_string = $_SERVER['QUERY_STRING'];
        }

        return self::buildUrl('current', $query_string, true);
    }

    public static function checkUrl(): bool
    {
        if (self::getCurrentUrl() !== self::getCleanUrl()) {
            Network::directHeader(self::getCleanUrl(), 301);
        }

        return true;
    }
}
