<?php

namespace Serato\SwsSdk;

use Serato\SwsSdk\Command;

/**
 * Adds HTTP Basic Authentication to a command.
 */
abstract class CommandBasicAuth extends Command
{
    /**
     * {@inheritdoc}
     */
    public function __construct($appId, $appPassword, $baseUri, array $args)
    {
        parent::__construct($appId, $appPassword, $baseUri, $args);
        $this->setBasicAuthHeader();
    }

    /**
     * Set the `Authorization` header for HTTP `Basic` authentication
     *
     * @return void
     */
    protected function setBasicAuthHeader()
    {
        $this->setRequestHeader(
            'Authorization',
            'Basic ' . base64_encode($this->appId . ':' . $this->appPassword)
        );
    }
}
