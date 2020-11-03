<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Ecom;

use Serato\SwsSdk\Sdk;
use Serato\SwsSdk\Client;

/**
 * Client used to interact with SWS E-commerce service.
 * @method \Serato\SwsSdk\Result getSubscriptions(array $args)
 * @method \Serato\SwsSdk\Result cancelSubscription(array $args)
 * @method \Serato\SwsSdk\Result createInvoice(array $args)
 */
class EcomClient extends Client
{
    /**
     * Get the base URI for the Client
     *
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->config[Sdk::BASE_URI][Sdk::BASE_URI_ECOM];
    }

    /**
     * Get an array of all valid commands for the Client.
     * The key of the array is command's name and the value is the Command
     * class name
     *
     * @return array<String, String>
     */
    public function getCommandMap(): array
    {
        return [
            'GetSubscriptions'      => '\\Serato\\SwsSdk\\Ecom\\Command\\SubscriptionList',
            'CancelSubscription'    => '\\Serato\\SwsSdk\\Ecom\\Command\\SubscriptionCancel',
            'CreateInvoice'         => '\\Serato\\SwsSdk\\Ecom\\Command\\InvoiceCreate'
        ];
    }
}
