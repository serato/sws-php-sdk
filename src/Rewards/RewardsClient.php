<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Rewards;

use Serato\SwsSdk\Rewards\Command\RefereeActivityList;
use Serato\SwsSdk\Rewards\Command\ReferralLogCreate;
use Serato\SwsSdk\Sdk;
use Serato\SwsSdk\Client;

/**
 * Client used to interact with SWS Rewards service.
 * @method \Serato\SwsSdk\Result createReferralLog(array $args)
 * @method \Serato\SwsSdk\Result getRefereeActivity(array $args)
 */
class RewardsClient extends Client
{
    /**
     * Get the base URI for the Client
     *
     * @return string
     */
    #[\Override]
    public function getBaseUri(): string
    {
        return $this->config[Sdk::BASE_URI][Sdk::BASE_URI_REWARDS];
    }

    /**
     * Get an array of all valid commands for the Client.
     * The key of the array is command's name and the value is the Command
     * class name
     *
     * @return array<String, String>
     */
    #[\Override]
    public function getCommandMap(): array
    {
        return [
            'CreateReferralLog' => ReferralLogCreate::class,
            'GetRefereeActivity' => RefereeActivityList::class
        ];
    }
}
