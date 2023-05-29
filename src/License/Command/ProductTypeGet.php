<?php

declare(strict_types=1);

namespace Serato\SwsSdk\License\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Gets information about Product Types from the SWS License service.
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `product_type_id`: (integer) Required. Product type id of the Product to create
 *
 * This command can be excuted on a `Serato\SwsSdk\License\LicenseClient` instance
 * using the `LicenseClient::getProductType` magic method.
 */
class ProductTypeGet extends CommandBasicAuth
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
        return '/api/v1/products/types/' . self::toString($this->commandArgs['product_type_id']);
    }

    /**
     * {@inheritdoc}
     */
    protected function getArgsDefinition(): array
    {
        return [
            'product_type_id' => ['type' => self::ARG_TYPE_INTEGER, 'required' => true]
        ];
    }
}
