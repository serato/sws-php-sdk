<?php

namespace Serato\SwsSdk\Exception;

use Serato\SwsSdk\Exception\ErrorMessageResponseException;

/**
 * An exception raised when a request to an SWS web service results in a
 * `503 Service Unavailable` HTTP response.
 */
class ServiceUnavailableException extends ErrorMessageResponseException
{
    protected function getHttpResponseName()
    {
        return '503 Service Unavailable';
    }
}
