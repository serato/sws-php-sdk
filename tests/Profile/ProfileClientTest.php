<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Test\Profile;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Profile\ProfileClient;
use Serato\SwsSdk\Sdk;

class ProfileClientTest extends AbstractTestCase
{
    const PROFILE_SERVER_BASE_URI = 'http://profile.server.com';

    public function testGetBaseUri()
    {
        $client = new ProfileClient(
            [
                Sdk::BASE_URI => [
                    Sdk::BASE_URI_ID        => 'https://id.server.com',
                    Sdk::BASE_URI_LICENSE   => 'http://license.server.com',
                    Sdk::BASE_URI_ECOM      => 'http://ecom.server.com',
                    Sdk::BASE_URI_PROFILE   => self::PROFILE_SERVER_BASE_URI,
                    Sdk::BASE_URI_DA      => 'http://da.server.com',
                    Sdk::BASE_URI_NOTIFICATIONS      => 'http://notifications.server.com',
                ]
            ],
            'my_app',
            'my_pass'
        );

        $this->assertEquals(self::PROFILE_SERVER_BASE_URI, $client->getBaseUri());
    }

    /* The remaining tests are smoke tests for each magic method provided by the client */

    public function testGetUser()
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createProfileClient();
        $result = $client->getUser(['user_id' => 123]);
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }

    public function testUpdateUser()
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createProfileClient();
        $result = $client->updateUser(['user_id' => 123]);
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }

    public function testGetUserBetaProgram()
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createProfileClient();
        $result = $client->getUserBetaProgram(['user_id' => 123]);
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }

    public function testAddUserBetaProgram()
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createProfileClient();
        $result = $client->addUserBetaProgram(['user_id' => 123, 'beta_program_id' => 'test-prog-id']);
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }

    public function testValidateAllUserBetaPrograms()
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createProfileClient();
        $result = $client->validateAllUserBetaPrograms(['user_id' => 123]);
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }

    public function testPartnerPromotionAddUser()
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createProfileClient();
        $result = $client->parterPromotionAddUser(['user_id' => 123, 'promotion_name' => 'test-promo']);
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }
}
