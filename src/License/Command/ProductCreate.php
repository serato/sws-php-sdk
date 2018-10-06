<?php

namespace Serato\SwsSdk\License\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Creates a new Product in the SWS License service.
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `product_type_id`: (integer) Required. Product type id of the Product to create
 * - `user_id`: (integer) User id to assign as the owner of the Product.
 * - `user_email_address`: (string) User email address to assign as the owner of the
 *   product when the user does not currently have a user account.
 * - `reseller_name`: (string) Name of reseller who purchased the product.
 * - `valid_to`: (DateTime) Date at which the licenses associated with the Product expire.
 * - `checkout_order_id`: (integer) Serato Checkout Order ID. Must be accompanied by
 *   the `checkout_order_item_id` parameter.
 * - `checkout_order_item_id`: (integer) Serato Checkout Order Item ID. Must be
 *   accompanied by the `checkout_order_id` parameter.
 * - `magento_order_id`: (integer) Magento Order ID. Must be accompanied by the
 *   `magento_order_item_id` parameter.
 * - `magento_order_item_id`: (integer) Magento Order Item ID. Must be accompanied
 *   by the `magento_order_id` parameter.
 *
 * One of `user_id`, `user_email_address` or `reseller_name` is required.
 *
 * One of a combination of `checkout_order_id` / `checkout_order_item_id` or
 * `magento_order_id` / `magento_order_item_id` is required.
 *
 * This command can be excuted on a `Serato\SwsSdk\License\LicenseClient` instance
 * using the `LicenseClient::createProduct` magic method.
 */
class ProductCreate extends CommandBasicAuth
{
    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->arrayToFormUrlEncodedBody($this->commandArgs);
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
        return '/api/v1/products/products';
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
            'product_type_id'           => ['type' => self::ARG_TYPE_INTEGER, 'required' => true],
            'user_id'                   => ['type' => self::ARG_TYPE_INTEGER],
            'user_email_address'        => ['type' => self::ARG_TYPE_STRING],
            'reseller_name'             => ['type' => self::ARG_TYPE_STRING],
            'valid_to'                  => ['type' => self::ARG_TYPE_DATETIME],
            'checkout_order_id'         => ['type' => self::ARG_TYPE_INTEGER],
            'checkout_order_item_id'    => ['type' => self::ARG_TYPE_INTEGER],
            'magento_order_id'          => ['type' => self::ARG_TYPE_INTEGER],
            'magento_order_item_id'     => ['type' => self::ARG_TYPE_INTEGER],
        ];
    }
}
