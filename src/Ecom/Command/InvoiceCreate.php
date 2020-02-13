<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Ecom\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Creates an invoice for an order.
 * 
 * Parameters:
 *  - 'order_id' int (required): The order ID to create the invoice for
 */
class InvoiceCreate extends CommandBasicAuth
{
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
        return "/api/v1/orders/{$this->commandArgs['order_id']}/invoice";
    }

    /**
     * {@inheritdoc}
     */
    protected function getArgsDefinition(): array
    {
        return [
            'order_id' => ['type' => self::ARG_TYPE_INTEGER, 'required' => true]
        ];
    }
}
