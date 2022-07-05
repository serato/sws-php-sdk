<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\Da;

use Serato\SwsSdk\Da\DaClient;
use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Sdk;

class DaClientTest extends AbstractTestCase
{
    private const DA_SERVER_BASE_URI = 'http://da.server.com';

    public function testGetBaseUri(): void
    {
        $client = new DaClient(
            [
                Sdk::BASE_URI => [
                    Sdk::BASE_URI_ID        => 'https://id.server.com',
                    Sdk::BASE_URI_LICENSE   => 'http://license.server.com',
                    Sdk::BASE_URI_PROFILE   => 'http://ecom.server.com',
                    Sdk::BASE_URI_DA      => self::DA_SERVER_BASE_URI,
                    Sdk::BASE_URI_ECOM      => 'http://ecom.server.com',
                    Sdk::BASE_URI_NOTIFICATIONS      => 'http://notifications.server.com',
                    Sdk::BASE_URI_REWARDS => 'http://rewards.server.com'
                ]
            ],
            'my_app',
            'my_pass'
        );

        $this->assertEquals(self::DA_SERVER_BASE_URI, $client->getBaseUri());
    }
}
