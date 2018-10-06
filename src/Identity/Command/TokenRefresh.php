<?php

namespace Serato\SwsSdk\Identity\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Requests an updated access token.
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `refresh_token`: (string) Required. A valid refresh token.
 *
 * This command can be executed on a `Serato\SwsSdk\Identity\IdentityClient` instance
 * using the `IdentityClient::tokenRefresh` magic method.
 */
class TokenRefresh extends CommandBasicAuth
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
    public function getHttpMethod()
    {
        return 'POST';
    }

    /**
     * {@inheritdoc}
     */
    public function getUriPath()
    {
        return '/api/v1/tokens/refresh';
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
            'refresh_token' => ['type' => self::ARG_TYPE_STRING, 'required' => true]
        ];
    }
}
