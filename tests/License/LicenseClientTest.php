<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\License;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\License\LicenseClient;
use Serato\SwsSdk\Sdk;

class LicenseClientTest extends AbstractTestCase
{
    private const LICENSE_SERVER_BASE_URI = 'http://license.server.com';

    public function testGetBaseUri(): void
    {
        $client = new LicenseClient(
            [
                Sdk::BASE_URI => [
                    Sdk::BASE_URI_ID        => 'https://id.server.com',
                    Sdk::BASE_URI_LICENSE   => self::LICENSE_SERVER_BASE_URI,
                    Sdk::BASE_URI_PROFILE   => 'https://profile.server.com',
                    Sdk::BASE_URI_ECOM      => 'http://ecom.server.com',
                    Sdk::BASE_URI_DA      => 'http://da.server.com',
                    Sdk::BASE_URI_NOTIFICATIONS      => 'http://notifications.server.com',
                    Sdk::BASE_URI_REWARDS => 'http://rewards.server.com'
                ]
            ],
            'my_app',
            'my_pass'
        );

        $this->assertEquals(self::LICENSE_SERVER_BASE_URI, $client->getBaseUri());
    }

    /* The remaining tests are smoke tests for each magic method provided by the client */

    public function testGetProducts(): void
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createLicenseClient();
        $result = $client->getProducts([]);
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }

    public function testGetProduct(): void
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createLicenseClient();
        $result = $client->getProduct(['product_id' => '123']);
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }

    public function testCreateProduct(): void
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createLicenseClient();
        $result = $client->createProduct(['product_type_id' => 123]);
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }

    public function testUpdateProduct(): void
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createLicenseClient();
        $result = $client->updateProduct(['product_id' => '123']);
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }

    public function testDeleteProduct(): void
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
