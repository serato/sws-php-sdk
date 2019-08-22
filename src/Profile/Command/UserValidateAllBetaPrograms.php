<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Profile\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Validates membership of all beta programs for an authenticated user.
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `user_id`: (integer) Required. User ID.
 *
 * This command can be executed on a `Serato\SwsSdk\Profile\ProfileClient` instance
 * using the `ProfileClient::validateAllUserBetaPrograms` magic method.
 */
class UserValidateAllBetaPrograms extends CommandBasicAuth
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
        return 'POST';
    }

    /**
     * {@inheritdoc}
     */
    public function getUriPath(): string
    {
        return '/api/v1/users/' . $this->commandArgs['user_id'] . '/betaprograms/validateall';
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
            'user_id' => ['type' => self::ARG_TYPE_INTEGER, 'required' => true]
        ];
    }
}
