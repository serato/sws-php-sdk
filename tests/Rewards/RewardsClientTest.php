<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\Rewards;

use Serato\SwsSdk\Rewards\RewardsClient;
use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Sdk;

class RewardsClientTest extends AbstractTestCase
{
    private const REWARDS_SERVER_BASE_URI = 'http://rewards.server.com';

    public function testGetBaseUri(): void
    {
        $client = new RewardsClient(
            [
                Sdk::BASE_URI => [
                    Sdk::BASE_URI_ID        => 'https://id.server.com',
                    Sdk::BASE_URI_LICENSE   => 'http://license.server.com',
                    Sdk::BASE_URI_PROFILE   => 'http://profile.server.com',
                    Sdk::BASE_URI_ECOM      => 'http://ecom.server.com',
                    Sdk::BASE_URI_DA      => 'http://da.server.com',
                    Sdk::BASE_URI_NOTIFICATIONS      => 'http://notifications.server.com',
                    Sdk::BASE_URI_REWARDS      => self::REWARDS_SERVER_BASE_URI
                ]
            ],
            'my_app',
            'my_pass'
        );

        $this->assertEquals(self::REWARDS_SERVER_BASE_URI, $client->getBaseUri());
    }

    /* Testing magic methods (smoke test) */
    public function testCreateReferralLog(): void
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createRewardsClient();
        $result = $client->createReferralLog(['code' => 'sdfsdfsdfsd',
            'referrer_user_id' => 3423432,
            'voucher_id' => 'ewrwerwerwerwe',
            'voucher_type_id' => 324234,
            'voucher_batch_id' => 'RF35S'
           ]);
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }

    /* Testing magic methods (smoke test) */
    public function testGetRefereeActivity(): void
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createRewardsClient();
        $result = $client->getRefereeActivity(['code' => 'sdfsdfsdfsd',
            'uid' => 3423432
        ]);
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }
}
