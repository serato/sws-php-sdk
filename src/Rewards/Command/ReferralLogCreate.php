<?php


namespace Serato\SwsSdk\Rewards\Command;


use Serato\SwsSdk\CommandBasicAuth;

/**
 * Logs a referrer or referee activity for a referral campaign.
 * Class ReferralLogCreate
 * @package Serato\SwsSdk\Rewards\Command
 */
class ReferralLogCreate extends CommandBasicAuth
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
        return '/api/v1/referralcode/' .
            self::toString($this->commandArgs['code']) .
            '/log';
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
            'code' => ['type' => self::ARG_TYPE_STRING, 'required' => true],
            'referrer_user_id' => ['type' => self::ARG_TYPE_INTEGER, 'required' => false], # EITHER this on
            # `referee_user_id` is required.
            'referee_user_id' => ['type' => self::ARG_TYPE_INTEGER, 'required' => false], # EITHER this on
            # `referrer_user_id` is required.
            'voucher_id' => ['type' => self::ARG_TYPE_STRING, 'required' => false], # EITHER this on
            # `product_id` is required.
            'product_id' => ['type' => self::ARG_TYPE_INTEGER, 'required' => false] # EITHER this on
            # `voucher_id` is required.
        ];
    }
}
