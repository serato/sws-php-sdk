<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\Identity;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Identity\IdentityClient;
use Serato\SwsSdk\Sdk;

class IdentityClientTest extends AbstractTestCase
{
    private const ID_SERVER_BASE_URI = 'http://id.server.com';

    public function testGetBaseUri(): void
    {
        $client = new IdentityClient(
            [
                Sdk::BASE_URI => [
                    Sdk::BASE_URI_ID        => self::ID_SERVER_BASE_URI,
                    Sdk::BASE_URI_LICENSE   => 'http://license.server.com',
                    Sdk::BASE_URI_PROFILE   => 'https://profile.server.com',
                    Sdk::BASE_URI_ECOM      => 'http://ecom.server.com',
                    Sdk::BASE_URI_DA      => 'http://da.server.com',
                    Sdk::BASE_URI_NOTIFICATIONS      => 'http://notifications.server.com',
                ]
            ],
            'my_app',
            'my_pass'
        );

        $this->assertEquals(self::ID_SERVER_BASE_URI, $client->getBaseUri());
    }

    /* The remaining tests are smoke tests for each magic method provided by the client */

    public function testGetUser(): void
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createIdentityClient();
        $result = $client->getUser();
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }

    public function testGetUsers(): void
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createIdentityClient();
        $result = $client->getUsers([]);
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }

    public function testUserAddGaClientId(): void
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createIdentityClient();
        $result = $client->userAddGaClientId(['user_id' => 123, 'ga_client_id' => 'abc']);
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }

    public function testTokenExchange(): void
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createIdentityClient();
        $result = $client->tokenExchange(['grant_type' => 'boo', 'code' => 'abc', 'redirect_uri' => 'uri']);
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }

    public function testTokenRefresh(): void
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createIdentityClient();
        $result = $client->tokenRefresh(['refresh_token' => 'str']);
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }

    public function testUserGroupAdd(): void
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createIdentityClient();
        $result = $client->addUserGroup(["user_id" => 123, 'group_name' => 'Test BetaProgram']);

        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }

    public function testUserGroupRemove(): void
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createIdentityClient();
        $result = $client->removeUserGroup(["user_id" => 123, 'group_name' => 'Test BetaProgram']);

        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }
}
