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
        $userId = self::toString($this->commandArgs['user_id']);
        return "/api/v1/users/{$userId}/billingaddress";
    }

    /**
     * {@inheritdoc}
     */
    protected function setCommandRequestHeaders(): void
    {
        $this->setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    }

    /**
     * {@inheritdoc}
     */
    protected function getArgsDefinition(): array
    {
        return [
            'user_id' => [
                'type' => self::ARG_TYPE_INTEGER,
                'required' => true
            ],
            'city' => [
                'type'     => self::ARG_TYPE_STRING,
                'required' => false
            ],
            'zip' => [
                'type'     => self::ARG_TYPE_STRING,
                'required' => false
            ],
            'country_code' => [
                'type'     => self::ARG_TYPE_STRING,
                'required' => false
            ],
            'state' => [
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
