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

class MessageStack
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

    /**
     *
     * @var array
     */
    protected static $messages = [];

    private function __construct()
    {
    }

    public static function addMessage(string $class, string $type, array $parameter): bool
    {
        self::$messages[$class][$type][] = $parameter;

        return true;
    }

    public static function getMessages(?string $class = null, ?string $type = null): array
    {
        if ($class !== null) {
            if (isset(self::$messages[$class])) {
                if ($type !== null) {
                    if (isset(self::$messages[$class][$type])) {
                        return self::$messages[$class][$type];
                    }
                } elseif (isset(self::$messages[$class])) {
                    return self::$messages[$class];
                }
            }

            return [];
        }

        return self::$messages;
    }

    public static function clearMessages(?string $class = null, ?string $type = null): bool
    {
        if ($class !== null) {
            if (isset(self::$messages[$class])) {
                if ($type !== null) {
                    if (isset(self::$messages[$class][$type])) {
                        unset(self::$messages[$class][$type]);

                        return true;
                    }
                } elseif (isset(self::$messages[$class])) {
                    unset(self::$messages[$class]);

                    return true;
                }
            }
        } else {
            self::$messages = [];

            return true;
        }

        return false;
    }

    public static function getMessageCount(string $class, string $type = ''): int
    {
        if ($type !== '') {
            if (isset(self::$messages[$class][$type])) {
                return \count(self::$messages[$class][$type]);
            }
        } else {
            if (isset(self::$messages[$class])) {
                return \count(self::$messages[$class]);
            }
        }

        return 0;
    }
}
