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

class ErrorLogger
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
     * Setzt das Level des ErrorReportings in PHP.
     *
     */
    public static function setPHPErrorReporting(int $level = \E_ALL): int
    {
        return error_reporting($level);
    }

    public static function getOutput(string $error_status): bool
    {
        $error_header = '';
        switch ($error_status) {
            case '401':
                $error_header = 'HTTP/1.1 401 Unauthorized';
                Network::sendHeader($error_header);

                break;
            case '402':
                $error_header = 'HTTP/1.1 402 Payment Required';
                Network::sendHeader($error_header);

                break;
            case '403':
                $error_header = 'HTTP/1.1 403 Forbidden';
                Network::sendHeader($error_header);

                break;
            case '404':
                $error_header = 'HTTP/1.1 404 Not Found';
                Network::sendHeader($error_header);

                break;
            case '405':
                $error_header = 'HTTP/1.1 405 Method Not Allowed';
                Network::sendHeader($error_header);

                break;
            case '406':
                $error_header = 'HTTP/1.1 406 Not Acceptable';
                Network::sendHeader($error_header);

                break;
            case '407':
                $error_header = 'HTTP/1.1 407 Proxy Authentication Required';
                Network::sendHeader($error_header);

                break;
            case '408':
                $error_header = 'HTTP/1.1 408 Request Timeout';
                Network::sendHeader($error_header);

                break;
            case '409':
                $error_header = 'HTTP/1.1 409 Conflict';
                Network::sendHeader($error_header);

                break;
            case '410':
                $error_header = 'HTTP/1.1 410 Gone';
                Network::sendHeader($error_header);

                break;
            case '411':
                $error_header = 'HTTP/1.1 411 Length Required';
                Network::sendHeader($error_header);

                break;
            case '412':
                $error_header = 'HTTP/1.1 412 Precondition Failed';
                Network::sendHeader($error_header);

                break;
            case '413':
                $error_header = 'HTTP/1.1 413 Request Entity Too Large';
                Network::sendHeader($error_header);

                break;
            case '414':
                $error_header = 'HTTP/1.1 414 Request-URI Too Long';
                Network::sendHeader($error_header);

                break;
            case '415':
                $error_header = 'HTTP/1.1 415 Unsupported Media Type';
                Network::sendHeader($error_header);

                break;
            case '416':
                $error_header = 'HTTP/1.1 416 Requested Range Not Satisfiable';
                Network::sendHeader($error_header);

                break;
            case '417':
                $error_header = 'HTTP/1.1 417 Expectation Failed';
                Network::sendHeader($error_header);

                break;
            case '400':
            default:
                $error_status = '400';
                $error_header = 'HTTP/1.1 400 Bad Request';
                Network::sendHeader($error_header);

                break;
        }

        if (!isset($_SERVER['REDIRECT_URL'])) {
            $_SERVER['REDIRECT_URL'] = '';
        }

        if (!isset($_SERVER['HTTP_REFERER'])) {
            $_SERVER['HTTP_REFERER'] = '';
        }

        if (!isset($_SERVER['HTTP_USER_AGENT'])) {
            $_SERVER['HTTP_USER_AGENT'] = '';
        }

        MessageStack::addMessage(self::getNameAsString(), 'error_' . $error_status, [
            'time' => time(),
            'line' => __LINE__,
            'function' => __FUNCTION__,
            'error_status' => $error_status,
            'remote_addr' => getenv('REMOTE_ADDR'),
            'redirect_url' => $_SERVER['REDIRECT_URL'],
            'http_referer' => $_SERVER['HTTP_REFERER'],
            'http_user_agent' => $_SERVER['HTTP_USER_AGENT'],
        ]);

        echo '<div style="width:60%; margin:auto auto; margin-top:10%; padding:20px; border:1px solid #999; background-color:#efefef; font-family:verdana; border-radius:3px;">';
        echo '<h1>' . $error_header . '</h1>';
        echo 'URL: ' . HTML::outputString(getenv('REDIRECT_URL')) . '<br/>';
        echo '<br/>';
        echo '<a href="/">Startpage</a>';
        echo '</div>';

        return true;
    }
}
