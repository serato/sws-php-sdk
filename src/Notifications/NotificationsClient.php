<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Notifications;

use Serato\SwsSdk\Sdk;
use Serato\SwsSdk\Client;

/**
 * Client used to interact with SWS Notifications service.
 */
class NotificationsClient extends Client
{
    /**
     * Get the base URI for the Client
     *
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->config[Sdk::BASE_URI][Sdk::BASE_URI_NOTIFICATIONS];
    }

    /**
     * Get an array of all valid commands for the Client.
     * The key of the array is command's name and the value is the Command
     * class name
     *
     * @return array<String, String>
     */
    public function getCommandMap(): array
    {
        return [];
    }
}
