<?php

namespace Serato\SwsSdk\Exception;

use Serato\SwsSdk\Exception\ResponseException;

/**
 * An exception whose decoded response body contains a `message` key.
 *
 * The HTTP response code is assigned to the `code` property of the exception.
 */
abstract class ErrorMessageResponseException extends ResponseException
{
    protected function getResultMessage()
    {
        return "Message: " . trim($this->getResult()['message']);
    }

    protected function getResultCode()
    {
        return (int)$this->getResult()->getResponse()->getStatusCode();
    }
}
