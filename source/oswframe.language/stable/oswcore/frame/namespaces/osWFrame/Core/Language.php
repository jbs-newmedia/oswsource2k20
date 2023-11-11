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

class Language
{
    use BaseStaticTrait;
    use BaseVarStaticTrait;

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

    /**
     * Array zum Speichern der verfügbaren Sprachen.
     *
     */
    protected static array $languages_available = [];

    protected static array $languages_short_available = [];

    /**
     * @var array
     */
    protected static $language_vars = [];

    protected static array $module2name = [];

    protected static array $name2module_lower = [];

    protected static array $name2module = [];

    protected static string $current_language_full = '';

    protected static string $current_language_short = '';

    private function __construct()
    {
    }

    /**
     * Setzt die verfügbaren Sprachen.
     *
     */
    public static function setAvailableLanguages(array $languages_available): void
    {
        self::$languages_short_available = $languages_available;
        self::$languages_available = array_combine($languages_available, $languages_available);
    }

    public static function getAvailableLanguages(): array
    {
        return self::$languages_available;
    }

    public static function getAvailableLanguagesShort(): array
    {
        return self::$languages_short_available;
    }

    public static function initLanguage(string $language = ''): bool
    {
        if ($language === '') {
            $language = Settings::getStringVar('project_default_language');
        }

        return self::setCurrentLanguage($language);
    }

    public static function setCurrentLanguageFull(string $current_language_full): void
    {
        self::$current_language_full = $current_language_full;
    }

    public static function getCurrentLanguageFull(): string
    {
        return self::$current_language_full;
    }

    public static function setCurrentLanguageShort(string $current_language_short): void
    {
        self::$current_language_short = $current_language_short;
    }

    public static function getCurrentLanguageShort(): string
    {
        return self::$current_language_short;
    }

    public static function setCurrentLanguage(string $language): bool
    {
        if (!isset(self::$languages_available[$language])) {
            return false;
        }

        $language_explode = explode('_', $language);
        self::setCurrentLanguageShort($language_explode[0]);
        self::setCurrentLanguageFull($language);

        return true;
    }

    public static function getCurrentLanguage(string $format = 'full'): string
    {
        switch ($format) {
            case 'short':
                return self::getCurrentLanguageShort();

                break;
            case 'full':
            default:
                return self::getCurrentLanguageFull();
        }
    }

    public static function setModuleName(string $module, string $name, string $language = ''): bool
    {
        if ($language === '') {
            $language = self::getCurrentLanguage();
        }
        self::$module2name[$language][$module] = $name;
        self::$name2module[$language][$name] = $module;
        self::$name2module_lower[$language][$name] = strtolower($module);

        return true;
    }

    public static function setModuleNames(array $module2name, string $language = ''): bool
    {
        foreach ($module2name as $module => $name) {
            self::setModuleName($module, $name, $language);
        }

        return true;
    }

    public static function getModuleName(string $module, string $language = ''): string
    {
        if ($language === '') {
            $language = self::getCurrentLanguage();
        }
        if (!isset(self::$module2name)) {
            return $module;
        }
        if (!isset(self::$module2name[$language])) {
            return $module;
        }
        if (!isset(self::$module2name[$language][$module])) {
            return $module;
        }

        return self::$module2name[$language][$module];
    }

    public static function getNameModule(string $name, string $language = ''): string
    {
        if ($language === '') {
            $language = self::getCurrentLanguage();
        }
        if (!isset(self::$name2module)) {
            return $name;
        }
        if (!isset(self::$name2module[$language])) {
            return $name;
        }
        if (!isset(self::$name2module[$language][$name])) {
            return $name;
        }

        return self::$name2module[$language][$name];
    }

    public static function getNameModuleLower(string $name, string $language = ''): string
    {
        if ($language === '') {
            $language = self::getCurrentLanguage();
        }
        if (!isset(self::$name2module_lower)) {
            return $name;
        }
        if (!isset(self::$name2module_lower[$language])) {
            return $name;
        }
        if (!isset(self::$name2module_lower[$language][$name])) {
            return $name;
        }

        return self::$name2module_lower[$language][$name];
    }

    public static function loadLanguageFile(
        string $file,
        $module = 'project',
        $dir = 'modules',
        string $language = ''
    ): bool {
        if ($language === '') {
            $language = self::getCurrentLanguage();
        }
        $module = self::getModuleByShort($module);
        $filename = Settings::getStringVar('settings_abspath');
        if ($dir !== '') {
            $filename .= $dir . \DIRECTORY_SEPARATOR;
        }
        if ($module !== '') {
            $filename .= $module . \DIRECTORY_SEPARATOR;
        }
        $filename .= 'lng' . \DIRECTORY_SEPARATOR . $language . \DIRECTORY_SEPARATOR . $file . '.json';
        if (Filesystem::existsFile($filename)) {
            $json = json_decode(file_get_contents($filename), true);
            if (isset($json['var'])) {
                foreach ($json['var'] as $key => $value) {
                    self::$language_vars[$language][$key] = $value;
                }
            }

            if (isset($json['module'])) {
                self::setModuleNames($json['module'], $language);
            }

            return true;
        }

        return false;
    }

    public static function getLanguageVar(string $var, string $language = '', bool $ucfirst = true): string
    {
        if ($language === '') {
            $language = self::getCurrentLanguage();
        }
        if ((isset(self::$language_vars[$language])) && (isset(self::$language_vars[$language][$var]))) {
            if ($ucfirst === true) {
                return ucfirst(self::$language_vars[$language][$var]);
            }

            return self::$language_vars[$language][$var];
        }

        if ($ucfirst === true) {
            return ucfirst($var);
        }

        return $var;
    }

    public static function addLanguage2Url(
        string $url,
        string $language_value,
        string $language_var = 'language'
    ): string {
        if (strpos($url, '?') === 0) {
            $url .= '?';
        } else {
            $url .= '&';
        }
        $url .= $language_var . '=' . $language_value;

        return $url;
    }

    /**
     * @param string $module
     */
    protected static function getModuleByShort($module = 'project'): string
    {
        if ($module === 'project') {
            return Settings::getStringVar('project_default_module');
        } elseif ($module === 'default') {
            return Settings::getStringVar('frame_default_module');
        } elseif ($module === 'current') {
            return Settings::getStringVar('frame_current_module');
        }

        return $module;
    }
}
