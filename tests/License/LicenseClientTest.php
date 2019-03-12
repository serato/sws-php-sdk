<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Test\License;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\License\LicenseClient;
use Serato\SwsSdk\Sdk;

class LicenseClientTest extends AbstractTestCase
{
    const LICENSE_SERVER_BASE_URI = 'http://license.server.com';

    public function testGetBaseUri()
    {
        $client = new LicenseClient(
            [
                Sdk::BASE_URI => [
                    Sdk::BASE_URI_ID        => 'https://id.server.com',
                    Sdk::BASE_URI_LICENSE   => self::LICENSE_SERVER_BASE_URI,
                    Sdk::BASE_URI_PROFILE   => 'https://profile.server.com',
                    Sdk::BASE_URI_ECOM      => 'http://ecom.server.com'
                ]
            ],
            'my_app',
            'my_pass'
        );

        $this->assertEquals(self::LICENSE_SERVER_BASE_URI, $client->getBaseUri());
    }

    /* The remaining tests are smoke tests for each magic method provided by the client */

    public function testGetProducts()
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createLicenseClient();
        $result = $client->getProducts();
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }

    public function testGetProduct()
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createLicenseClient();
        $result = $client->getProduct(['product_id' => '123']);
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }

    public function testCreateProduct()
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createLicenseClient();
        $result = $client->createProduct(['product_type_id' => 123]);
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }

    public function testUpdateProduct()
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createLicenseClient();
        $result = $client->updateProduct(['product_id' => '123']);
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }

    public function testDeleteProduct()
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createLicenseClient();
        $result = $client->deleteProduct(['product_id' => '123']);
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }
}
