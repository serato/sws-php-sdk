<?php

namespace Serato\SwsSdk\Exception;

use Serato\SwsSdk\Exception\ErrorMessageResponseException;

/**
 * An exception raised when a request to an SWS web service results in a
 * `500 Application Error` HTTP response.
 */
class ServerApplicationErrorException extends ErrorMessageResponseException
{
    protected function getHttpResponseName()
    {
        return '500 Application Error';
    }
}
