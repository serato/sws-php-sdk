<?php

namespace Serato\SwsSdk\Ecom\Command;

use Serato\SwsSdk\CommandBasicAuth;

class VoucherRedeem extends CommandBasicAuth
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
        return 'PUT';
    }

    /**
     * {@inheritdoc}
     */
    public function getUriPath(): string
    {
        return '/api/v1/users/' .
            self::toString($this->commandArgs['user_id']) .
            '/vouchers/' .
            self::toString($this->commandArgs['voucher_id']);
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
                'type'     => self::ARG_TYPE_INTEGER,
                'required' => true
            ],
            'voucher_id' => [
                'type'     => self::ARG_TYPE_STRING,
                'required' => true
            ]
        ];
    }
}
