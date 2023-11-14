<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\Ecom;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Ecom\EcomClient;
use Serato\SwsSdk\Sdk;

class EcomClientTest extends AbstractTestCase
{
    private const ECOM_SERVER_BASE_URI = 'http://ecom.server.com';

    public function testGetBaseUri(): void
    {
        $client = new EcomClient(
            [
                Sdk::BASE_URI => [
                    Sdk::BASE_URI_ID        => 'https://id.server.com',
                    Sdk::BASE_URI_LICENSE   => 'http://license.server.com',
                    Sdk::BASE_URI_PROFILE   => 'http://ecom.server.com',
                    Sdk::BASE_URI_ECOM      => self::ECOM_SERVER_BASE_URI,
                    Sdk::BASE_URI_DA      => 'http://da.server.com',
                    Sdk::BASE_URI_NOTIFICATIONS      => 'http://notifications.server.com',
                    Sdk::BASE_URI_REWARDS => 'http://rewards.server.com'
                ]
            ],
            'my_app',
            'my_pass'
        );

        $this->assertEquals(self::ECOM_SERVER_BASE_URI, $client->getBaseUri());
    }

    /* Testing magic methods (smoke test) */
    public function testSubscriptionList(): void
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createEcomClient();
        $result = $client->getSubscriptions(['user_id' => 123]);
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }

    public function testCancelSubscription(): void
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createEcomClient();
        $result = $client->cancelSubscription(['user_id' => 123, 'subscription_id' => '233-12121']);
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }

    public function testCreateInvoice(): void
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createEcomClient();
        $result = $client->createInvoice(
            [
                'order_id' => 123,
                'transaction_reference' => 'mock_transaction_ref',
                'payment_instrument_transaction_reference' => 'mock_payment_instrument_transaction_ref'
            ]
        );
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }

    public function testGetInvoicesSummary(): void
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createEcomClient();
        $result = $client->getInvoicesSummary(['date' => '2020-05-14']);
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }

    public function testGetVouchers(): void
    {
        $body = '{"var1":"val1"}';
        $client = $this->getSdkWithMocked200Response($body)->createEcomClient();
        $result = $client->getVouchers(['user_id' => 123]);
        $this->assertEquals(
            (string)$this->getResponseObjectFromResult($result)->getBody(),
            $body
        );
    }
}
