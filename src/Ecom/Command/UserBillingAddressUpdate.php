<?php

namespace Serato\SwsSdk\Ecom\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Class UserBillingAddressUpdate
 *
 * Updates the billing address of all the active and pending subscriptions.
 *
 * @package Serato\SwsSdk\Ecom\Command
 */
class UserBillingAddressUpdate extends CommandBasicAuth
{
    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getBody(): string
    {
        $args = $this->commandArgs;
        unset($args['user_id']);
        return $this->arrayToFormUrlEncodedBody($args);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getHttpMethod(): string
    {
        return 'PUT';
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getUriPath(): string
    {
        $userId = self::toString($this->commandArgs['user_id']);
        return "/api/v1/users/{$userId}/billingaddress";
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
            'user_id' => [
                'type' => self::ARG_TYPE_INTEGER,
                'required' => true
            ],
            'first_name' => [
                'type'     => self::ARG_TYPE_STRING,
                'required' => false
            ],
            'last_name' => [
                'type'     => self::ARG_TYPE_STRING,
                'required' => false
            ],
            'country_code' => [
                'type'     => self::ARG_TYPE_STRING,
                'required' => false
            ],
            'post_code' => [
                'type'     => self::ARG_TYPE_STRING,
                'required' => false
            ],
            'region' => [
                'type'     => self::ARG_TYPE_STRING,
                'required' => false
            ],
            'city' => [
                'type'     => self::ARG_TYPE_STRING,
                'required' => false
            ],
            'company' => [
                'type'     => self::ARG_TYPE_STRING,
                'required' => false
            ],
            'address' => [
                'type'     => self::ARG_TYPE_STRING,
                'required' => false
            ],
            'address_extended' => [
                'type'     => self::ARG_TYPE_STRING,
                'required' => false
            ],
        ];
    }
}
