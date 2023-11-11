<?php declare(strict_types=0);

/**
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package   osWFrame
 * @link      https://oswframe.com
 * @license   MIT License
 */

namespace osWFrame\Core;

class DDM4Convert
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

    public static function addPreViewElement(?string $ddmgroup, string $element, array $options): void
    {
        self::convertElement('preview', $ddmgroup, $element, $options);
    }

    public static function addViewElement(?string $ddmgroup, string $element, array $options): void
    {
        self::convertElement('view', $ddmgroup, $element, $options);
    }

    public static function addDataElement(?string $ddmgroup, string $element, array $options): void
    {
        self::convertElement('data', $ddmgroup, $element, $options);
    }

    public static function addFinishElement(?string $ddmgroup, string $element, array $options): void
    {
        self::convertElement('finish', $ddmgroup, $element, $options);
    }

    public static function addAfterFinishElement(?string $ddmgroup, string $element, array $options): void
    {
        self::convertElement('afterfinish', $ddmgroup, $element, $options);
    }

    public static function addSendELement(?string $ddmgroup, string $element, array $options): void
    {
        self::convertElement('send', $ddmgroup, $element, $options);
    }

    public static function convertElement(string $type, ?string $ddmgroup, string $element, array $options): void
    {
        $ar_ddm = [];
        $ar_ddm[] = '/*';
        if (isset($options['title'])) {
            $name = $options['title'];
        } else {
            $name = $element;
        }
        switch ($type) {
            case 'preview':
                $ar_ddm[] = ' * PreView: ' . $name;

                break;
            case 'view':
                $ar_ddm[] = ' * View: ' . $name;

                break;
            case 'data':
                $ar_ddm[] = ' * Data: ' . $name;

                break;
            case 'finish':
                $ar_ddm[] = ' * Finish: ' . $name;

                break;
            case 'afterfinish':
                $ar_ddm[] = ' * AfterFinish: ' . $name;

                break;
            case 'send':
                $ar_ddm[] = ' * Send: ' . $name;

                break;
        }
        $ar_ddm[] = ' */';
        $ar_ddm[] = '$ddm4_elements[\'' . $type . '\'][\'' . $element . '\']=[];';
        foreach ($options as $k1 => $v1) {
            if (\is_array($v1)) {
                $ar_ddm[] = '$ddm4_elements[\'' . $type . '\'][\'' . $element . '\'][\'' . $k1 . '\']=[];';
                foreach ($v1 as $k2 => $v2) {
                    if (\is_array($v2)) {
                        $ar_ddm[] = '$ddm4_elements[\'' . $type . '\'][\'' . $element . '\'][\'' . $k1 . '\'][\'' . $k2 . '\']=[];';
                        foreach ($v2 as $k3 => $v3) {
                            if (\is_array($v3)) {
                                $ar_ddm[] = '$ddm4_elements[\'' . $type . '\'][\'' . $element . '\'][\'' . $k1 . '\'][\'' . $k2 . '\'][\'' . $k3 . '\']=[];';
                                foreach ($v3 as $k4 => $v4) {
                                    if (\is_array($v4)) {
                                        $ar_ddm[] = '$ddm4_elements[\'' . $type . '\'][\'' . $element . '\'][\'' . $k1 . '\'][\'' . $k2 . '\'][\'' . $k3 . '\'][\'' . $k4 . '\']=[];';
                                    } else {
                                        $ar_ddm[] = '$ddm4_elements[\'' . $type . '\'][\'' . $element . '\'][\'' . $k1 . '\'][\'' . $k2 . '\'][\'' . $k3 . '\'][\'' . $k4 . '\']=' . self::escapeValue(
                                            $v4
                                        ) . ';';
                                    }
                                }
                            } else {
                                $ar_ddm[] = '$ddm4_elements[\'' . $type . '\'][\'' . $element . '\'][\'' . $k1 . '\'][\'' . $k2 . '\'][\'' . $k3 . '\']=' . self::escapeValue(
                                    $v3
                                ) . ';';
                            }
                        }
                    } else {
                        $ar_ddm[] = '$ddm4_elements[\'' . $type . '\'][\'' . $element . '\'][\'' . $k1 . '\'][\'' . $k2 . '\']=' . self::escapeValue(
                            $v2
                        ) . ';';
                    }
                }
            } else {
                $ar_ddm[] = '$ddm4_elements[\'' . $type . '\'][\'' . $element . '\'][\'' . $k1 . '\']=' . self::escapeValue($v1) . ';';
            }
        }

        echo '<pre>' . implode("\n", $ar_ddm) . '</pre><br/>';
    }

    /**
     * @return int|string|string[]
     */
    public static function escapeValue($value)
    {
        if (strstr($value, '$$$')) {
            return str_replace('$$$', '', $value);
        }
        if (strstr($value, '###')) {
            $replace = [];
            $replace['###osW_VIS2_Navigation::getInstance()->getPages'] = '\VIS2\Core\Manager::getPagesByToolId';
            $replace['###osW_VIS2::getInstance()->getToolId'] = '\VIS2\Core\Main::getToolId';
            $replace['###osW_VIS2_User::getInstance()->getId'] = '\VIS2\Core\User::getId';
            $replace['###osW_DDM4::getInstance()->getGroupOption($ddm_group, '] = '$osW_DDM4->getGroupOption(';
            $replace['###time()'] = 'time()';
            $replace['###osW_VIS2_Mandant::getInstance()->getMandantId()'] = '\VIS2\Core\Mandant::getId()';
            foreach ($replace as $k => $v) {
                $value = str_replace($k, $v, $value);
            }

            return $value;
        }
        if (\is_string($value)) {
            return '\'' . $value . '\'';
        }
        if (\is_int($value)) {
            return $value;
        }
        if (\is_bool($value)) {
            if ($value === true) {
                return 'true';
            }

            return 'false';
        }

        return '';
    }
}
