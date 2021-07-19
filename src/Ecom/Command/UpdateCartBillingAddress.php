<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Ecom\Command;

use Serato\SwsSdk\CommandBasicAuth;
use Exception;

/**
 * Class UpdateCartBillingAddress
 * @package Serato\SwsSdk\Ecom\Command
 */
class UpdateCartBillingAddress extends CommandBasicAuth
{
    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        $args = $this->commandArgs;
        unset($args['cart_uuid']);
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
        # A bit of defensive programming to keep phpstan happy :-)
        if (!is_string($this->commandArgs['cart_uuid'])) {
            throw new Exception(
                "Invalid type for 'cart_uuid' command argument. Expected type string. " .
                gettype($this->commandArgs['cart_uuid']) . " given."
            );
        }
        return "/api/v1/carts/{$this->commandArgs['cart_uuid']}/billingaddress";
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
            'cart_uuid' => [
                'type'     => self::ARG_TYPE_STRING,
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
            'company' => [
                'type'     => self::ARG_TYPE_STRING,
                'required' => false
            ],
            'address1' => [
                'type'     => self::ARG_TYPE_STRING,
                'required' => false
            ],
            'address2' => [
                'type'     => self::ARG_TYPE_STRING,
                'required' => false
            ],
            'city' => [
                'type'     => self::ARG_TYPE_STRING,
                'required' => false
            ],
            'zip' => [
                'type'     => self::ARG_TYPE_STRING,
                'required' => false
            ],
            'state' => [
                'type'     => self::ARG_TYPE_STRING,
                'required' => false
            ],
            'country_code' => [
                'type'     => self::ARG_TYPE_STRING,
                'required' => false
            ],
        ];
    }
}
