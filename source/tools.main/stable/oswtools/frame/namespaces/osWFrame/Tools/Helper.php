<?php declare(strict_types=0);

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license MIT License
 */

namespace osWFrame\Tools;

use osWFrame\Core as Frame;

class Helper
{
    use Frame\BaseStaticTrait;

    /**
     * Major-Version der Klasse.
     */
    private const CLASS_MAJOR_VERSION = 1;

    /**
     * Minor-Version der Klasse.
     */
    private const CLASS_MINOR_VERSION = 0;

    /**
     * Release-Version der Klasse.
     */
    private const CLASS_RELEASE_VERSION = 1;

    /**
     * Extra-Version der Klasse.
     * Zum Beispiel alpha, beta, rc1, rc2 ...
     */
    private const CLASS_EXTRA_VERSION = '';

    /**
     */
    protected static string $doaction = '';

    /**
     */
    private function __construct()
    {
    }

    /**
     */
    public static function setDoAction(string $doaction): bool
    {
        self::$doaction = $doaction;

        return true;
    }

    /**
     */
    public static function getDoAction(): string
    {
        return self::$doaction;
    }

    /**
     * @param string $v1 current
     * @param string $v2 update
     */
    public static function checkVersion(string $v1, string $v2): bool
    {
        $v1 = explode('.', $v1);
        $v2 = explode('.', $v2);

        if ((\count($v1) !== 2) || (\count($v2) !== 2)) {
            return true;
        }

        if (((int) ($v1[0])) < ((int) ($v2[0]))) {
            return true;
        }
        if (((int) ($v1[1])) < ((int) ($v2[1]))) {
            return true;
        }

        return false;
    }

    /**
     */
    public static function getLicense(string $license): string
    {
        switch ($license) {
            case 'GNU General Public License':
            case 'GNU General Public License 3':
                return str_replace(['<a name="', '<a ', '<a target="_blank" id="'], ['<a id="', '<a target="_blank" ', '<a id="'], file_get_contents(Frame\Settings::getStringVar('settings_abspath') . 'resources' . \DIRECTORY_SEPARATOR . 'license' . \DIRECTORY_SEPARATOR . 'gpl-3.0.html'));

                break;
            case 'MIT License':
                return file_get_contents(Frame\Settings::getStringVar('settings_abspath') . 'resources' . \DIRECTORY_SEPARATOR . 'license' . \DIRECTORY_SEPARATOR . 'mit.html');

                break;
            default:
                return 'license (' . $license . ') not found';

                break;
        }
    }
}
