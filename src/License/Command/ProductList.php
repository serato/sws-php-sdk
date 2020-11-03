<?php
declare(strict_types=1);

namespace Serato\SwsSdk\License\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Gets a filtered list of Products from the SWS License service.
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `checkout_order_id`: (integer) Serato Checkout Order ID.
 * - `magento_order_id`: (integer) Magento Order ID.
 * - `user_id`: (integer) User ID.
 *
 * This command can be excuted on a `Serato\SwsSdk\License\LicenseClient` instance
 * using the `LicenseClient::getProducts` magic method.
 */
class ProductList extends CommandBasicAuth
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
        return '/api/v1/products/products';
    }

    /**
     * {@inheritdoc}
     */
    public function getUriQuery(): string
    {
        return http_build_query($this->commandArgs);
    }

    /**
     * {@inheritdoc}
     */
    protected function getArgsDefinition(): array
    {
        return [
            'checkout_order_id' => ['type' => self::ARG_TYPE_INTEGER, 'required' => false],
            'magento_order_id'  => ['type' => self::ARG_TYPE_INTEGER, 'required' => false],
            'user_id'           => ['type' => self::ARG_TYPE_INTEGER, 'required' => false]
        ];
    }
}
