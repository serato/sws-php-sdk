<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Exception;

use Serato\SwsSdk\Exception\ErrorCodeResponseException;

/**
 * An exception raised when a request to an SWS web service results in a
 * `401 Unauthorized` HTTP response.
 */
class UnauthorizedException extends ErrorCodeResponseException
{
    protected function getHttpResponseName(): string
    {
        return '401 Unauthorized';
    }
}
