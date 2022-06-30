<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Rewards\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * This method returns the activity of a referee user related to a referral campaign.
 * Also determines their eligibility for a referee reward.
 * Class RefereeActivityList
 * @package Serato\SwsSdk\Rewards\Command
 */
class RefereeActivityList extends CommandBasicAuth
{
    /**
     * {@inheritdoc}
     */
    public function getBody(): string
    {
        return $this->arrayToFormUrlEncodedBody($this->commandArgs);

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
            '/api/v1/referralcode/' . self::toString($this->commandArgs['code']) .
            '/referee/' . self::toString($this->commandArgs['uid']);
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
            'code'      => ['type' => self::ARG_TYPE_STRING, 'required' => true],
            'uid'   => ['type' => self::ARG_TYPE_INTEGER, 'required' => false]
        ];
    }
}
