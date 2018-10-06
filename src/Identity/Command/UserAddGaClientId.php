<?php

namespace Serato\SwsSdk\Identity\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Adds a Google Client ID association to a user via the SWS Identity service.
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `user_id`: (integer) Required. User ID.
 * - `ga_client_id`: (string) Required. Google Analytics Client ID.
 *
 * This command can be excuted on a `Serato\SwsSdk\Identity\IdentityClient` instance
 * using the `IdentityClient::userAddGaClientId` magic method.
 */
class UserAddGaClientId extends CommandBasicAuth
{
    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        $args = $this->commandArgs;
        unset($args['user_id']);
        return $this->arrayToFormUrlEncodedBody($args);
    }

    /**
     * {@inheritdoc}
     */
    public function getHttpMethod()
    {
        return 'POST';
    }

    /**
     * {@inheritdoc}
     */
    public function getUriPath()
    {
        return '/api/v1/users/' . $this->commandArgs['user_id'] . '/gaclientid';
    }

    /**
     * {@inheritdoc}
     */
    protected function setCommandRequestHeaders()
    {
        $this->setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    }

    /**
     * {@inheritdoc}
     */
    protected function getArgsDefinition()
    {
        return [
            'user_id'       => ['type' => self::ARG_TYPE_INTEGER, 'required' => true],
            'ga_client_id'  => ['type' => self::ARG_TYPE_STRING, 'required' => true]
        ];
    }
}
