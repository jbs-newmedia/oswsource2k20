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

class SessionMessageStack
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

    public static function addMessage(string $class, string $type, array $parameter): bool
    {
        $messageToStack = self::loadSessionMessageStack();
        $messageToStack[$class][$type][] = $parameter;

        return self::saveSessionMessageStack($messageToStack);
    }

    /**
     * Gibt die Nachrichten zurück.
     *
     */
    public static function getMessages(?string $class = null, ?string $type = null): array
    {
        $messageToStack = self::loadSessionMessageStack();
        if ($class !== null) {
            if (isset($messageToStack[$class])) {
                if ($type !== null) {
                    if (isset($messageToStack[$class][$type])) {
                        self::saveSessionMessageStack($messageToStack);

                        return $messageToStack[$class][$type];
                    }
                } elseif (isset($messageToStack[$class])) {
                    self::saveSessionMessageStack($messageToStack);

                    return $messageToStack[$class];
                }
            }

            return [];
        }
        self::saveSessionMessageStack($messageToStack);

        return $messageToStack;
    }

    /**
     * Leert die Nachrichten.
     *
     */
    public static function clearMessages(?string $class = null, ?string $type = null): bool
    {
        $messageToStack = self::loadSessionMessageStack();
        if ($class !== null) {
            if (isset($messageToStack[$class])) {
                if ($type !== null) {
                    if (isset($messageToStack[$class][$type])) {
                        unset($messageToStack[$class][$type]);
                        self::saveSessionMessageStack($messageToStack);

                        return true;
                    }
                } elseif (isset($messageToStack[$class])) {
                    unset($messageToStack[$class]);
                    self::saveSessionMessageStack($messageToStack);

                    return true;
                }
            }
        } else {
            $messageToStack = [];
            self::saveSessionMessageStack($messageToStack);

            return true;
        }

        return false;
    }

    protected static function loadSessionMessageStack(): array
    {
        if (Session::isVar('messageToStack')) {
            return Session::getArrayVar('messageToStack');
        }

        return [];
    }

    protected static function saveSessionMessageStack(array $messageToStack): bool
    {
        return Session::setArrayVar('messageToStack', $messageToStack);
    }
}
