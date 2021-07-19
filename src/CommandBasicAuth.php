<?php

declare(strict_types=1);

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
    public function __construct(string $appId, string $appPassword, string $baseUri, array $args)
    {
        parent::__construct($appId, $appPassword, $baseUri, $args);
        $this->setBasicAuthHeader();
    }

    /**
     * Set the `Authorization` header for HTTP `Basic` authentication
     *
     * @return void
     */
    protected function setBasicAuthHeader(): void
    {
        $this->setRequestHeader(
            'Authorization',
            'Basic ' . base64_encode($this->appId . ':' . $this->appPassword)
        );
    }
}
