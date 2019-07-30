<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Identity\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Adds the authenticated client user to a user group
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
    public function getHttpMethod(): string
    {
        return 'DELETE';
    }

    /**
     * {@inheritdoc}
     */
    public function getUriPath(): string
    {
        return '/api/v1/users/'. $this->commandArgs['user_id'] .'/groups/'. $this->commandArgs['group_name'];
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
            'group_name' => ['type' => self::ARG_TYPE_STRING, 'required' => true]
        ];
    }
}
