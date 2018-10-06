<?php

namespace Serato\SwsSdk\License\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Updates a Product in the SWS License service.
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `product_id`: (string) Required. Product ID.
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
 * One of a combination of `checkout_order_id` / `checkout_order_item_id` or
 * `magento_order_id` / `magento_order_item_id` is required.
 *
 * This command can be excuted on a `Serato\SwsSdk\License\LicenseClient` instance
 * using the `LicenseClient::updateProduct` magic method.
 */
class ProductUpdate extends CommandBasicAuth
{
    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        $args = $this->commandArgs;
        unset($args['product_id']);
        return $this->arrayToFormUrlEncodedBody($args);
    }

    /**
     * {@inheritdoc}
     */
    public function getHttpMethod()
    {
        return 'PUT';
    }

    /**
     * {@inheritdoc}
     */
    public function getUriPath()
    {
        return '/api/v1/products/products/' . $this->commandArgs['product_id'];
    }

    /**
     * {@inheritdoc}
     */
    protected function setCommandRequestHeaders()
    {
        $this->setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    }

    /**
     * {@inheritdoc}
     */
    protected function getArgsDefinition()
    {
        return [
            'product_id'                => ['type' => self::ARG_TYPE_STRING, 'required' => true],
            'valid_to'                  => ['type' => self::ARG_TYPE_DATETIME],
            'checkout_order_id'         => ['type' => self::ARG_TYPE_INTEGER],
            'checkout_order_item_id'    => ['type' => self::ARG_TYPE_INTEGER],
            'magento_order_id'          => ['type' => self::ARG_TYPE_INTEGER],
            'magento_order_item_id'     => ['type' => self::ARG_TYPE_INTEGER],
        ];
    }
}
