<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Identity\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Requests an updated access token.
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `refresh_token`: (string) Required. A valid refresh token.
 * - `use_rotation`: (boolean) When true, rotates the refresh token on every call. Default it is true.
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
    public function getHttpMethod(): string
    {
        return 'POST';
    }

    /**
     * {@inheritdoc}
     */
    public function getUriPath(): string
    {
        // By default we use token rotation if it is not set
        $useRotation = true;
        if (isset($this->commandArgs['use_rotation'])) {
            $useRotation = $this->commandArgs['use_rotation'];
        }
        return $useRotation ? '/api/v2/tokens/refresh' : '/api/v1/tokens/refresh';
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
            'refresh_token' => ['type' => self::ARG_TYPE_STRING, 'required' => true],
            'use_rotation'  => ['type' => self::ARG_TYPE_BOOLEAN, 'required' => false]
        ];
    }
}
