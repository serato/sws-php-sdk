<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Ecom\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Gets a filtered list of Vouchers from the SWS Ecom service.
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `user_id`: (integer) Return vouchers that belong to the user id. user_id is required.
 *
 * This command can be excuted on a `Serato\SwsSdk\Ecom\EcomClient` instance
 * using the `EcomClient::getVouchers` magic method.
 */
class VoucherList extends CommandBasicAuth
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
        return 'GET';
    }

    /**
     * {@inheritdoc}
     */
    public function getUriPath(): string
    {
        return
        '/api/v1/users/' .
        self::toString($this->commandArgs['user_id']) .
        '/vouchers';
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
            'user_id'       => ['type' => self::ARG_TYPE_INTEGER, 'required' => true]
        ];
    }
}
