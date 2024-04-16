<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Identity\Command;

use Serato\SwsSdk\CommandBearerTokenAuth;

/**
 * Gets user information for an authenticated user.
 *
 * Requires a bearer token to identify the authenticated user.
 *
 * This command can be excuted on a `Serato\SwsSdk\Identity\IdentityClient` instance
 * using the `IdentityClient::getUser` magic method.
 */
class UserGet extends CommandBearerTokenAuth
{
    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getHttpMethod(): string
    {
        return 'GET';
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getUriPath(): string
    {
        return '/api/v1/me';
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    protected function getArgsDefinition(): array
    {
        return [];
    }
}
