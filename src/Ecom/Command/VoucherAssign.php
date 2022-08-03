<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Ecom\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Assign a voucher to a user.
 *
 * Parameters:
 *  - 'voucher_id': (string) Required.
 *
 * This command can be executed on a `Serato\SwsSdk\Ecom\EcomClient` instance
 * using the `EcomClient::assignVoucher` magic method.
 */
class VoucherAssign extends CommandBasicAuth
{
    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->arrayToFormUrlEncodedBody($this->commandArgs);
    }

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
        return '/api/v1/users/' .
                self::toString($this->commandArgs['user_id']) .
                '/vouchers';
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
            'user_id' => ['type' => self::ARG_TYPE_INTEGER, 'required' => true],
            'voucher_id' => ['type' => self::ARG_TYPE_STRING, 'required' => true],
            'referral_code' => ['type' => self::ARG_TYPE_STRING, 'required' => false]
        ];
    }
}
