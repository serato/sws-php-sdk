<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Profile\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Attach a User to a Partner Promotion code.
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `user_id`: (integer) Required. User ID.
 * - `promotion_name`: (string) Required. Name of partner promotion.
 *
 * This command can be excuted on a `Serato\SwsSdk\Profile\ProfileClient` instance
 * using the `ProfileClient::parterPromotionAddUser` magic method.
 */
class PartnerPromotionAddUser extends CommandBasicAuth
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
        return '/api/v1/partnerpromotions/code';
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
            'user_id'               => ['type' => self::ARG_TYPE_INTEGER, 'required' => true],
            'promotion_name'        => ['type' => self::ARG_TYPE_STRING, 'required' => true]
        ];
    }
}
