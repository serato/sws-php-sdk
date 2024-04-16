<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Profile\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Add a User in the Beta program.
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `user_id`: (integer) Required. User ID.
 * - `beta_program_id`: (string) Required Beta Program ID.
 * This command can be excuted on a `Serato\SwsSdk\Profile\ProfileClient` instance
 * using the `ProfileClient::addUserBetaProgram` magic method.
 */
class UserAddBetaProgram extends CommandBasicAuth
{
    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getBody()
    {
        $args = $this->commandArgs;
        unset($args['user_id']);
        return $this->arrayToFormUrlEncodedBody($args);
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
        return '/api/v1/users/' . self::toString($this->commandArgs['user_id']) . '/betaprograms';
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
            'user_id'               => ['type' => self::ARG_TYPE_INTEGER, 'required' => true],
            'beta_program_id'       => ['type' => self::ARG_TYPE_STRING, 'required' => true]
        ];
    }
}
