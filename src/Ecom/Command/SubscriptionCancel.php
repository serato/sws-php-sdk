<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Ecom\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Cancel a subscription
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `user_id`: (integer) Required. User ID.
 * - `subscription_id`: (integer) Required. Subscription ID.
 *
 * This command can be executed on a `Serato\SwsSdk\Ecom\EcomClient` instance
 * using the `EcomClient::cancelSubscription` magic method.
 */
class SubscriptionCancel extends CommandBasicAuth
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
        return 'DELETE';
    }

    /**
     * {@inheritdoc}
     */
    public function getUriPath(): string
    {
        return
            '/api/v1/users/' .
            $this->commandArgs['user_id'] .
            '/subscriptions/' .
            $this->commandArgs['subscription_id'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getArgsDefinition(): array
    {
        return [
            'user_id' => ['type' => self::ARG_TYPE_INTEGER, 'required' => true],
            'subscription_id' => ['type' => self::ARG_TYPE_STRING, 'required' => true]
        ];
    }
}
