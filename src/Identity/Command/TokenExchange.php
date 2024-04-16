<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Identity\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Exchanges a temporary authorization token for Access and Refresh tokens.
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `grant_type`: (string) Required. Always `authorization_code`.
 * - `code`: (string) Required. The authorizaton code being exchanged.
 * - `redirect_uri`: (string) Required. The redirect URI supplied when the authorization code was issued.
 *
 * This command can be executed on a `Serato\SwsSdk\Identity\IdentityClient` instance
 * using the `IdentityClient::tokenExchange` magic method.
 */
class TokenExchange extends CommandBasicAuth
{
    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getBody()
    {
        return $this->arrayToFormUrlEncodedBody($this->commandArgs);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getHttpMethod(): string
    {
        return 'POST';
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getUriPath(): string
    {
        return '/api/v1/tokens/exchange';
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    protected function setCommandRequestHeaders(): void
    {
        $this->setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    protected function getArgsDefinition(): array
    {
        return [
            'grant_type'    => ['type' => self::ARG_TYPE_STRING, 'required' => true],
            'code'          => ['type' => self::ARG_TYPE_STRING, 'required' => true],
            'redirect_uri'  => ['type' => self::ARG_TYPE_STRING, 'required' => true]
        ];
    }
}
