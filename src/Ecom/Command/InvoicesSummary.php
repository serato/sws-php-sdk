<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Ecom\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Get subscription invoices summary
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `date`: (string) Compulsory.
 *
 * This command can be executed on a `Serato\SwsSdk\Ecom\EcomClient` instance
 * using the `EcomClient::getInvoicesSummary` magic method.
 */
class InvoicesSummary extends CommandBasicAuth
{
    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getHttpMethod(): string
    {
        return 'GET';
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getUriPath(): string
    {
        return
            '/api/v1/invoices/summary';
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getUriQuery(): string
    {
        return http_build_query($this->commandArgs);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    protected function getArgsDefinition(): array
    {
        return [
            'date' => ['type' => self::ARG_TYPE_STRING, 'required' => true]
        ];
    }
}
