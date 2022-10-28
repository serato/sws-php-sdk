<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Ecom\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * List subscriptions of a user
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `user_id`: (integer) Required. User ID.
 *
 * This command can be executed on a `Serato\SwsSdk\Ecom\EcomClient` instance
 * using the `EcomClient::getSubscriptions` magic method.
 */
class SubscriptionList extends CommandBasicAuth
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
        return
            '/api/v1/users/' .
            self::toString($this->commandArgs['user_id']) .
            '/subscriptions';
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
