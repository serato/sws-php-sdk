<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Identity\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Requests an updated access token.
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `refresh_token`: (string) Required. A valid refresh token.
 *
 * This command can be executed on a `Serato\SwsSdk\Identity\IdentityClient` instance
 * using the `IdentityClient::tokenRefresh` magic method.
 */
class TokenRefresh extends CommandBasicAuth
{
    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getBody()
    {
        return $this->arrayToFormUrlEncodedBody($this->commandArgs);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getHttpMethod(): string
    {
        return 'POST';
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getUriPath(): string
    {
        return '/api/v1/tokens/refresh';
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    protected function setCommandRequestHeaders(): void
    {
        $this->setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    protected function getArgsDefinition(): array
    {
        return [
            'refresh_token' => ['type' => self::ARG_TYPE_STRING, 'required' => true]
        ];
    }
}
