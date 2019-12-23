<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Test\Notifications;

use Serato\SwsSdk\Da\DaClient;
use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Sdk;

class NotificationsClientTest extends AbstractTestCase
{
    const NOTIFICATIONS_SERVER_BASE_URI = 'http://da.server.com';

    public function testGetBaseUri()
    {
        $client = new DaClient(
            [
                Sdk::BASE_URI => [
                    Sdk::BASE_URI_ID        => 'https://id.server.com',
                    Sdk::BASE_URI_LICENSE   => 'http://license.server.com',
                    Sdk::BASE_URI_PROFILE   => 'http://ecom.server.com',
                    Sdk::BASE_URI_NOTIFICATIONS     => self::NOTIFICATIONS_SERVER_BASE_URI
                ]
            ],
            'my_app',
            'my_pass'
        );

        $this->assertEquals(self::NOTIFICATIONS_SERVER_BASE_URI, $client->getBaseUri());
    }
}
