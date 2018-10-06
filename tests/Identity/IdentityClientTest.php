<?php
namespace Serato\SwsSdk\Test\Identity;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Identity\IdentityClient;
use Serato\SwsSdk\Sdk;

class IdentityClientTest extends AbstractTestCase
{
    const ID_SERVER_BASE_URI = 'http://id.server.com';

    public function testGetBaseUri()
    {
        $client = new IdentityClient(
            'my_app',
            'my_pass',
            [
                Sdk::BASE_URI => [
                    Sdk::BASE_URI_ID        => self::ID_SERVER_BASE_URI,
                    Sdk::BASE_URI_LICENSE   => 'http://license.server.com'
                ]
            ]
        );

        $this->assertEquals(self::ID_SERVER_BASE_URI, $client->getBaseUri());
    }
}
