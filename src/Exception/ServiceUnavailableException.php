<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Exception;

use Serato\SwsSdk\Exception\ErrorMessageResponseException;

/**
 * An exception raised when a request to an SWS web service results in a
 * `503 Service Unavailable` HTTP response.
 */
class ServiceUnavailableException extends ErrorMessageResponseException
{
    #[\Override]
    protected function getHttpResponseName(): string
    {
        return '503 Service Unavailable';
    }
}
