<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Identity\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Adds the authenticated client user to a user group
  *
 * This command can be excuted on a `Serato\SwsSdk\Identity\IdentityClient` instance
 */
class UserGroupAdd extends CommandBasicAuth
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
    public function getHttpMethod(): string
    {
        return 'POST';
    }

    /**
     * {@inheritdoc}
     */
    public function getUriPath(): string
    {
        return '/api/v1/users/'. $this->commandArgs['user_id'] . '/groups';
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
            'user_id'   => ["type" => self::ARG_TYPE_INTEGER, "required" => true],
            'group_name' => [ "type" => self::ARG_TYPE_STRING, "required" => true]
        ];
    }
}
