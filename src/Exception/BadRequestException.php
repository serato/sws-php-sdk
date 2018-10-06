<?php

namespace Serato\SwsSdk\Exception;

use Serato\SwsSdk\Exception\ErrorCodeResponseException;

/**
 * An exception raised when a request to an SWS web service results in a
 * `400 Bad Request` HTTP response.
 */
class BadRequestException extends ErrorCodeResponseException
{
    protected function getHttpResponseName()
    {
        return '400 Bad Request';
    }
}
