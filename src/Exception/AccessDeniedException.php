<?php

namespace Serato\SwsSdk\Exception;

use Serato\SwsSdk\Exception\ErrorCodeResponseException;

/**
 * An exception raised when a request to an SWS web service results in a
 * `403 Access Denied` HTTP response.
 */
class AccessDeniedException extends ErrorCodeResponseException
{
    protected function getHttpResponseName()
    {
        return '403 Access Denied';
    }
}
