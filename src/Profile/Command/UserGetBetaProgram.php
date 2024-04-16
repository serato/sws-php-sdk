<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Profile\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Gets beta program list for an authenticated user.
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `user_id`: (integer) Required. User ID.
 *
 * This command can be executed on a `Serato\SwsSdk\Profile\ProfileClient` instance
 * using the `ProfileClient::getUserBetaProgram` magic method.
 */
class UserGetBetaProgram extends CommandBasicAuth
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
        return '/api/v1/users/' . self::toString($this->commandArgs['user_id']) . '/betaprograms';
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getUriQuery(): string
    {
        return http_build_query($this->commandArgs);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    protected function getArgsDefinition(): array
    {
        return [
            'user_id' => ['type' => self::ARG_TYPE_INTEGER, 'required' => true]
        ];
    }
}
