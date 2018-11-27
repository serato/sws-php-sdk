<?php

namespace Serato\SwsSdk\Profile\Command;

use Serato\SwsSdk\CommandBearerTokenAuth;

/**
 * Gets information for a logged in user.
 *
 * Requires a bearer token to identify the authenticated user.
 *
 * This command can be executed on a `Serato\SwsSdk\Profile\ProfileClient` instance
 * using the `ProfileClient::getMe` magic method.
 */
class MeGet extends CommandBearerTokenAuth
{
    /**
     * {@inheritdoc}
     */
    public function getHttpMethod(): string
    {
        return 'GET';
    }

    /**
     * {@inheritdoc}
     */
    public function getUriPath(): string
    {
        return '/api/v1/me';
    }

    /**
     * {@inheritdoc}
     */
    protected function getArgsDefinition(): array
    {
        return [];
    }
}
