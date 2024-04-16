<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Exception;

use Serato\SwsSdk\Exception\ResponseException;

/**
 * An exception whose decoded response body contains a `message` key.
 *
 * The HTTP response code is assigned to the `code` property of the exception.
 */
abstract class ErrorMessageResponseException extends ResponseException
{
    #[\Override]
    protected function getResultMessage(): string
    {
        return "Message: " . trim((string) $this->getResult()['message']);
    }

    #[\Override]
    protected function getResultCode(): int
    {
        $response = $this->getResult()->getResponse();
        if ($response === null) {
            return 0;
        } else {
            return (int)$response->getStatusCode();
        }
    }
}
