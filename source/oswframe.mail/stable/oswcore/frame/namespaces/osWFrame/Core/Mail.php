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

class Mail
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

    protected string $from = '';

    protected string $replyto = '';

    protected array $to = [];

    protected array $cc = [];

    protected array $bcc = [];

    protected string $subject = '';

    protected string $message = '';

    public function __construct()
    {
    }

    public function clear(): bool
    {
        $this->from = '';
        $this->replyto = '';
        $this->to = [];
        $this->cc = [];
        $this->bcc = [];
        $this->subject = '';
        $this->message = '';

        return true;
    }

    public function clearName(string $str): string
    {
        $str = preg_replace('/[<>"]/', ' ', $str);
        $str = preg_replace('/\s\s+/', ' ', $str);

        return $str;
    }

    public function setFrom(string $address, string $name = ''): bool
    {
        $this->from = [
            'address' => $address,
            'name' => $name,
        ];

        return true;
    }

    public function setReplyTo(string $address, string $name = ''): bool
    {
        $this->replyto = [
            'address' => $address,
            'name' => $name,
        ];

        return true;
    }

    public function addAddress(string $address, string $name = ''): bool
    {
        $this->to[] = [
            'address' => $address,
            'name' => $name,
        ];

        return true;
    }

    public function addCC(string $address, string $name = ''): bool
    {
        $this->cc[] = [
            'address' => $address,
            'name' => $name,
        ];

        return true;
    }

    public function addBCC(string $address, string $name = ''): bool
    {
        $this->bcc[] = [
            'address' => $address,
            'name' => $name,
        ];

        return true;
    }

    public function setSubject(string $subject): bool
    {
        $this->subject = $subject;

        return true;
    }

    public function setMessage(string $message): bool
    {
        $message = str_replace("\r\n", "\n", $message);
        $message = str_replace("\n\r", "\n", $message);
        $this->message = $message;

        return true;
    }

    public function send(string $charset = 'utf8'): bool
    {
        if (!isset($this->data['to'])) {
            return false;
        }
        $headers = '';
        $headers .= 'X-Mailer: PHP/' . \PHP_VERSION . "\n";
        $headers .= 'X-Sender-IP: ' . Network::getIPAddress() . "\n";
        if ($charset === 'iso') {
            $headers = '';
        } else {
            $headers .= "Content-type: text/plain; charset=utf-8\n";
        }
        if (isset($this->from)) {
            $headers .= 'From:' . $this->makeAddress($this->from['address'], $this->from['name']) . "\n";
        }
        if (isset($this->replyto)) {
            $headers .= 'Reply-To:' . $this->makeAddress($this->replyto['address'], $this->replyto['name']) . "\n";
        }
        $to = [];
        foreach ($this->to as $address) {
            $to[] = $this->makeAddress($address['address'], $address['name']);
        }
        $to = implode(',', $to);

        if ((isset($this->cc)) && (\count($this->cc) > 0)) {
            $cc = [];
            foreach ($this->cc as $address) {
                $cc[] = $this->makeAddress($address['address'], $address['name']);
            }
            $headers .= 'Cc: ' . implode(',', $cc) . "\n";
        }
        if ((isset($this->bcc)) && (\count($this->bcc) > 0)) {
            $bcc = [];
            foreach ($this->data['bcc'] as $address) {
                $bcc[] = $this->makeAddress($address['address'], $address['name']);
            }
            $headers .= 'Bcc: ' . implode(',', $bcc) . "\n";
        }

        if ($charset === 'iso') {
            mail(utf8_decode($to), utf8_decode($this->subject), utf8_decode($this->message), utf8_decode($headers));
        } else {
            mail($to, '=?utf-8?B?' . base64_encode($this->subject) . '?=', $this->message, $headers);
        }

        return true;
    }

    protected function makeAddress(string $address, string $name = ''): string
    {
        $name = $this->clearName($name);
        if ($name === '') {
            return $address;
        }

        return '"' . $name . '"<' . $address . '>';
    }
}
