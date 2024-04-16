<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Identity\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Remove the authenticated client user to a user group
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `group_name`: (string) Required. Group name.
 * This command can be excuted on a `Serato\SwsSdk\Identity\IdentityClient` instance
 */
class UserGroupRemove extends CommandBasicAuth
{
    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getHttpMethod(): string
    {
        return 'DELETE';
    }

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
    public function getUriPath(): string
    {
        return '/api/v1/users/' . self::toString($this->commandArgs['user_id']) .
                '/groups/' . self::toString($this->commandArgs['group_name']);
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
            'user_id'   => ["type" => self::ARG_TYPE_INTEGER, "required" => true],
            'group_name' => ['type' => self::ARG_TYPE_STRING, 'required' => true]
        ];
    }
}
