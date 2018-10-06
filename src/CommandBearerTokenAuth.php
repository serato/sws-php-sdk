<?php

namespace Serato\SwsSdk;

use Serato\SwsSdk\Command;

/**
 * Adds Bearer token based Authentication to a command.
 */
abstract class CommandBearerTokenAuth extends Command
{
    /**
     * {@inheritdoc}
     */
    public function getRequest($bearerToken = '')
    {
        $this->setBearerAuthHeader($bearerToken);
        return parent::getRequest();
    }

    /**
     * Set the `Authorization` header for bearer token authentication
     *
     * @return void
     */
    protected function setBearerAuthHeader($bearerToken)
    {
        $this->setRequestHeader('Authorization', 'Bearer ' . $bearerToken);
    }
}
