<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Ecom;

use Serato\SwsSdk\Sdk;
use Serato\SwsSdk\Client;
use Serato\SwsSdk\Ecom\Command\SubscriptionList;
use Serato\SwsSdk\Ecom\Command\SubscriptionCancel;
use Serato\SwsSdk\Ecom\Command\InvoiceCreate;
use Serato\SwsSdk\Ecom\Command\UpdateCartBillingAddress;
use Serato\SwsSdk\Ecom\Command\VoucherTypeList;
use Serato\SwsSdk\Ecom\Command\VoucherCreate;
use Serato\SwsSdk\Ecom\Command\VoucherAssign;
use Serato\SwsSdk\Ecom\Command\VoucherRedeem;
use Serato\SwsSdk\Ecom\Command\UserBillingAddressUpdate;
use Serato\SwsSdk\Ecom\Command\InvoicesSummary;
use Serato\SwsSdk\Ecom\Command\VoucherList;

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
 * @method \Serato\SwsSdk\Result getVouchers(array $args)
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
            'GetSubscriptions'         => SubscriptionList::class,
            'CancelSubscription'       => SubscriptionCancel::class,
            'CreateInvoice'            => InvoiceCreate::class,
            'UpdateCartBillingAddress' => UpdateCartBillingAddress::class,
            'GetVoucherTypes'          => VoucherTypeList::class,
            'CreateVoucher'            => VoucherCreate::class,
            'AssignVoucher'            => VoucherAssign::class,
            'RedeemVoucher'            => VoucherRedeem::class,
            'UpdateUserBillingAddress' => UserBillingAddressUpdate::class,
            'GetInvoicesSummary'       => InvoicesSummary::class,
            'GetVouchers'              => VoucherList::class
        ];
    }
}
