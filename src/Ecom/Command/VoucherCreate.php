<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Ecom\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Create a vouchers for the provided voucher type.
 *
 * Parameters:
 *  - 'voucher_type_id': (integer) Required.
 *
 * This command can be executed on a `Serato\SwsSdk\Ecom\EcomClient` instance
 * using the `EcomClient::createVoucher` magic method.
 */
class VoucherCreate extends CommandBasicAuth
{
    /**
     * {@inheritdoc}
     */
    public function getHttpMethod(): string
    {
        return 'POST';
    }

    /**
     * {@inheritdoc}
     */
    public function getUriPath(): string
    {
        return '/api/v1/vouchers/';
    }

    /**
     * {@inheritdoc}
     */
    protected function getArgsDefinition(): array
    {
        return [
            'voucher_type_id' => ['type' => self::ARG_TYPE_INTEGER, 'required' => true],
            'batch_id' => ['type' => self::ARG_TYPE_STRING, 'required' => true]
        ];
    }
}
