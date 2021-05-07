<?php
namespace Serato\SwsSdk\Ecom\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Class SubscriptionBillingAddressUpdate
 *
 * Updates the billing address of all the active and pending subscriptions.
 *
 * @package Serato\SwsSdk\Ecom\Command
 */
class SubscriptionBillingAddressUpdate extends CommandBasicAuth
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
        return 'PUT';
    }

    /**
     * {@inheritdoc}
     */
    public function getUriPath(): string
    {
        $userId = $this->commandArgs['user_id'];
        return "/api/v1/users/{$userId}/subscriptions/billingaddress";
    }

    /**
     * {@inheritdoc}
     */
    protected function getArgsDefinition(): array
    {
        return [
            'user_id' => ['type' => self::ARG_TYPE_INTEGER, 'required' => true],
        ];
    }
}
