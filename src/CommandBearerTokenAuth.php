<?php

declare(strict_types=1);

namespace Serato\SwsSdk;

use Serato\SwsSdk\Command;
use Psr\Http\Message\RequestInterface;

/**
 * Adds Bearer token based Authentication to a command.
 */
abstract class CommandBearerTokenAuth extends Command
{
    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getRequest(string $bearerToken = ''): RequestInterface
    {
        $this->setBearerAuthHeader($bearerToken);
        return parent::getRequest();
    }

    /**
     * Set the `Authorization` header for bearer token authentication
     *
     * @param string $bearerToken Bearer token
     * @return void
     */
    protected function setBearerAuthHeader(string $bearerToken): void
    {
        $this->setRequestHeader('Authorization', 'Bearer ' . $bearerToken);
    }
}
