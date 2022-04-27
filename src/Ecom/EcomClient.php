<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Ecom;

use Serato\SwsSdk\Sdk;
use Serato\SwsSdk\Client;

/**
 * Client used to interact with SWS E-commerce service.
 *
 * @method \Serato\SwsSdk\Result getSubscriptions(array $args)
 * @method \Serato\SwsSdk\Result cancelSubscription(array $args)
 * @method \Serato\SwsSdk\Result createInvoice(array $args)
 * @method \Serato\SwsSdk\Result updateCartBillingAddress(array $args)
 * @method \Serato\SwsSdk\Result getVoucherTypes(array $args)
 * @method \Serato\SwsSdk\Result createVoucher(array $args)
 * @method \Serato\SwsSdk\Result assignVoucher(array $args)
 * @method \Serato\SwsSdk\Result redeemVoucher(array $args)
 * @method \Serato\SwsSdk\Result updateUserBillingAddress(array $args)
 * @method \Serato\SwsSdk\Result getInvoicesSummary(array $args)
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
            'GetSubscriptions'         => '\\Serato\\SwsSdk\\Ecom\\Command\\SubscriptionList',
            'CancelSubscription'       => '\\Serato\\SwsSdk\\Ecom\\Command\\SubscriptionCancel',
            'CreateInvoice'            => '\\Serato\\SwsSdk\\Ecom\\Command\\InvoiceCreate',
            'UpdateCartBillingAddress' => '\\Serato\\SwsSdk\\Ecom\\Command\\UpdateCartBillingAddress',
            'GetVoucherTypes'          => '\\Serato\\SwsSdk\\Ecom\\Command\\VoucherTypeList',
            'CreateVoucher'            => '\\Serato\\SwsSdk\\Ecom\\Command\\VoucherCreate',
            'AssignVoucher'            => '\\Serato\\SwsSdk\\Ecom\\Command\\VoucherAssign',
            'RedeemVoucher'            => '\\Serato\\SwsSdk\\Ecom\\Command\\VoucherRedeem',
            'UpdateUserBillingAddress' => '\\Serato\\SwsSdk\\Ecom\\Command\\UserBillingAddressUpdate',
            'GetInvoicesSummary'       => '\\Serato\\SwsSdk\\Ecom\\Command\\InvoicesSummary'
        ];
    }
}
