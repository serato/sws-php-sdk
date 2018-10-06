<?php

namespace Serato\SwsSdk\License\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Gets information about a Product from the SWS License service.
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `product_id`: (string) Required. Product ID.
 *
 * This command can be excuted on a `Serato\SwsSdk\License\LicenseClient` instance
 * using the `LicenseClient::getProduct` magic method.
 */
class ProductGet extends CommandBasicAuth
{
    /**
     * {@inheritdoc}
     */
    public function getHttpMethod()
    {
        return 'GET';
    }

    /**
     * {@inheritdoc}
     */
    public function getUriPath()
    {
        return '/api/v1/products/products/' . $this->commandArgs['product_id'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getArgsDefinition()
    {
        return [
            'product_id' => ['type' => self::ARG_TYPE_STRING, 'required' => true]
        ];
    }
}
