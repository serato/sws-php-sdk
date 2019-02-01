<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Exception;

use Serato\SwsSdk\Exception\ResponseException;

/**
 * An exception whose decoded response body contains `code` and `error` keys.
 *
 * The response body's `code` key is assigned to the `code` property of the exception.
 */
abstract class ErrorCodeResponseException extends ResponseException
{
    protected function getResultMessage(): string
    {
        return "Code: " . $this->getResult()['code'] .
                "\nError: " . $this->getResult()['error'] . "\n";
    }

    protected function getResultCode(): int
    {
        return (int)$this->getResult()['code'];
    }
}
