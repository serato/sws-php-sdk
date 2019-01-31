<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Identity\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Gets a filtered list of Users from the SWS Identity service.
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `email_address`: (string) User's email address.
 * - `ga_client_id`: (string) User's Google Analytics Client ID.
 *
 * Note: If no filters are provided an empty list is returned.
 *
 * This command can be excuted on a `Serato\SwsSdk\Identity\IdentityClient` instance
 * using the `IdentityClient::getUsers` magic method.
 */
class UserList extends CommandBasicAuth
{
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
        return '/api/v1/users';
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
            'email_address' => ['type' => self::ARG_TYPE_STRING],
            'ga_client_id'  => ['type' => self::ARG_TYPE_STRING],
            # Part of the REST API spec, but deliberately ommitted from the
            # PHP docs because:
            # - Hopefully it will be deprecated or removed in the near future, and
            # - I can't think of a legitimate use case for sending it from a server
            #   side application
            # I've only included it for testing purposes
            'app_session_cookie' => ['type' => self::ARG_TYPE_STRING]
        ];
    }
}
