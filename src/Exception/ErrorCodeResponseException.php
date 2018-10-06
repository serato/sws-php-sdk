<?php

namespace Serato\SwsSdk\Exception;

use Serato\SwsSdk\Exception\ResponseException;

/**
 * An exception whose decoded response body contains `code` and `error` keys.
 *
 * The response body's `code` key is assigned to the `code` property of the exception.
 */
abstract class ErrorCodeResponseException extends ResponseException
{
    protected function getResultMessage()
    {
        return "Code: " . $this->getResult()['code'] .
                "\nError: " . $this->getResult()['error'] . "\n";
    }

    protected function getResultCode()
    {
        return (int)$this->getResult()['code'];
    }
}
