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

namespace osWFrame\Api;

trait BaseReturnTrait
{
    protected bool $error = false;

    protected string $error_message = '';

    protected string $success_message = '';

    public function setError(bool $error): bool
    {
        $this->error = $error;

        return true;
    }

    public function getError(): bool
    {
        return $this->error;
    }

    public function setErrorMessage(string $error_message): bool
    {
        $this->error_message = $error_message;

        return true;
    }

    public function getErrorMessage(): string
    {
        return $this->error_message;
    }

    public function setSuccessMessage(string $success_message): bool
    {
        $this->success_message = $success_message;

        return true;
    }

    public function getSuccessMessage(): string
    {
        return $this->success_message;
    }
}
