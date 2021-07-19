<?php

declare(strict_types=1);

namespace Serato\SwsSdk\License\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Gets a filtered list of Licenses from the SWS License service.
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `app_name`: (string) Only return licenses compatible with the host application.
 * - `app_version`: (string) Only return licenses compatible with the host application version.
 * - `term`: (string) Only return licenses of the specified term.
 * - `user_id`: (integer) Return licenses that belong to the user id. user_id is required.
 *
 * This command can be excuted on a `Serato\SwsSdk\License\LicenseClient` instance
 * using the `LicenseClient::getLicenses` magic method.
 */
class LicenseList extends CommandBasicAuth
{
    /**
     * {@inheritdoc}
     */
    public function getBody(): string
    {
        $args = $this->commandArgs;
        unset($args['user_id']);
        return $this->arrayToFormUrlEncodedBody($args);
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
        '/api/v1/users/' .
        self::toString($this->commandArgs['user_id']) .
        '/licenses';
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
            'app_name'      => ['type' => self::ARG_TYPE_STRING, 'required' => false],
            'app_version'   => ['type' => self::ARG_TYPE_STRING, 'required' => false],
            'term'          => ['type' => self::ARG_TYPE_STRING, 'required' => false],
            'user_id'       => ['type' => self::ARG_TYPE_INTEGER, 'required' => true]
        ];
    }
}
