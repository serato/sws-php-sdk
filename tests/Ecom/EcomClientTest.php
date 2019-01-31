<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Test\Ecom;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Ecom\EcomClient;
use Serato\SwsSdk\Sdk;

class EcomClientTest extends AbstractTestCase
{
    const ECOM_SERVER_BASE_URI = 'http://ecom.server.com';

    public function testGetBaseUri()
    {
        $client = new EcomClient(
            [
                Sdk::BASE_URI => [
                    Sdk::BASE_URI_ID        => 'https://id.server.com',
                    Sdk::BASE_URI_LICENSE   => 'http://license.server.com',
                    Sdk::BASE_URI_PROFILE   => 'http://ecom.server.com',
                    Sdk::BASE_URI_ECOM      => self::ECOM_SERVER_BASE_URI
                ]
            ],
            'my_app',
            'my_pass'
        );

        $this->assertEquals(self::ECOM_SERVER_BASE_URI, $client->getBaseUri());
    }
}
