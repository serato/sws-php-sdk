<?php

namespace Serato\SwsSdk\Identity;

use Serato\SwsSdk\Sdk;
use Serato\SwsSdk\Client;

/**
 * Client used to interact with the SWS Identity service.
 *
 * @method \Psr\Http\Message\RequestInterface getUsers(array $args)
 * @method \Psr\Http\Message\RequestInterface userAddGaClientId(array $args)
 */
class IdentityClient extends Client
{
    /**
     * Get the base URI for the Client
     *
     * @return string
     */
    public function getBaseUri()
    {
        return $this->config[Sdk::BASE_URI][Sdk::BASE_URI_ID];
    }

    /**
     * Get an array of all valid commands for the Client.
     * The key of the array is command's name and the value is the Command
     * class name
     *
     * @return array
     */
    public function getCommandMap()
    {
        return [
            'GetUser'           => '\\Serato\\SwsSdk\\Identity\\Command\\UserGet',
            'GetUsers'          => '\\Serato\\SwsSdk\\Identity\\Command\\UserList',
            'UserAddGaClientId' => '\\Serato\\SwsSdk\\Identity\\Command\\UserAddGaClientId',
            'TokenExchange'     => '\\Serato\\SwsSdk\\Identity\\Command\\TokenExchange',
            'TokenRefresh'      => '\\Serato\\SwsSdk\\Identity\\Command\\TokenRefresh',
        ];
    }
}
