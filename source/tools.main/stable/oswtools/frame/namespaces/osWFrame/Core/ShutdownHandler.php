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

class ShutdownHandler
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

    /**
     * Setzt den Shutdownhandler des Frames.
     *
     */
    public static function setHandler(): bool
    {
        register_shutdown_function('osWFrame\Core\Shutdownhandler::handleError');

        return true;
    }

    public static function handleError(): bool
    {
        $last_error = error_get_last();
        if (($last_error !== null) && ($last_error['type'] === \E_ERROR)) {
            MessageStack::addMessage(self::getNameAsString(), 'fatal', [
                'time' => time(),
                'errno' => $last_error['type'],
                'errstr' => $last_error['message'],
                'errfile' => $last_error['file'],
                'errline' => $last_error['line'],
            ]);
        }

        MessageWriter::writeLogs();

        return true;
    }
}
