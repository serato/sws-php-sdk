<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Profile\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Gets user information for an authenticated user.
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `user_id`: (integer) Required. User ID.
 *
 * This command can be executed on a `Serato\SwsSdk\Profile\ProfileClient` instance
 * using the `ProfileClient::getUser` magic method.
 */
class UserGet extends CommandBasicAuth
{
    /**
     * {@inheritdoc}
     */
    public function getBody(): string
    {
        $args = $this->commandArgs;
        unset($args['user_id']);
        return $this->arrayToFormUrlEncodedBody($args);
    }

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
        return '/api/v1/users/' . self::toString($this->commandArgs['user_id']);
    }

    /**
     * {@inheritdoc}
     */
    protected function getArgsDefinition(): array
    {
        return [
            'user_id' => ['type' => self::ARG_TYPE_INTEGER, 'required' => true]
        ];
    }
}
