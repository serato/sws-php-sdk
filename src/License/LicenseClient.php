<?php

declare(strict_types=1);

namespace Serato\SwsSdk\License;

use Serato\SwsSdk\Sdk;
use Serato\SwsSdk\Client;

/**
 * Client used to interact with SWS License service.
 *
 * @method \Serato\SwsSdk\Result getProducts(array $args)
 * @method \Serato\SwsSdk\Result getProduct(array $args)
 * @method \Serato\SwsSdk\Result createProduct(array $args)
 * @method \Serato\SwsSdk\Result updateProduct(array $args)
 * @method \Serato\SwsSdk\Result deleteProduct(array $args)
 * @method \Serato\SwsSdk\Result getLicenses(array $args)
 */
class LicenseClient extends Client
{
    /**
     * Get the base URI for the Client
     *
     * @return string
     */
    #[\Override]
    public function getBaseUri(): string
    {
        return $this->config[Sdk::BASE_URI][Sdk::BASE_URI_LICENSE];
    }

    /**
     * Get an array of all valid commands for the Client.
     * The key of the array is command's name and the value is the Command
     * class name
     *
     * @return array<String, String>
     */
    #[\Override]
    public function getCommandMap(): array
    {
        return [
            'GetProducts'       => \Serato\SwsSdk\License\Command\ProductList::class,
            'GetProduct'        => \Serato\SwsSdk\License\Command\ProductGet::class,
            'CreateProduct'     => \Serato\SwsSdk\License\Command\ProductCreate::class,
            'UpdateProduct'     => \Serato\SwsSdk\License\Command\ProductUpdate::class,
            'DeleteProduct'     => \Serato\SwsSdk\License\Command\ProductDelete::class,
            'GetLicenses'       => \Serato\SwsSdk\License\Command\LicenseList::class
        ];
    }
}
