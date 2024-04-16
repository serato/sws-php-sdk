<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Ecom\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Get list of all available offers
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `product_type_id`: (integer) Optional.
 * - `offer_type`: (string) Optional.
 *
 * This command can be executed on a `Serato\SwsSdk\Ecom\EcomClient` instance
 * using the `EcomClient::getOffers` magic method.
 */
class VoucherTypeList extends CommandBasicAuth
{
    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getHttpMethod(): string
    {
        return 'GET';
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getUriPath(): string
    {
        return
            '/api/v1/vouchers/types';
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getUriQuery(): string
    {
        return http_build_query($this->commandArgs);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    protected function getArgsDefinition(): array
    {
        return [
            'product_type_id' => ['type' => self::ARG_TYPE_INTEGER, 'required' => false],
            'voucher_type' => ['type' => self::ARG_TYPE_STRING, 'required' => false]
        ];
    }
}
