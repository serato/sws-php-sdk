<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\Ecom\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Ecom\Command\SubscriptionList;

class SubscriptionListTest extends AbstractTestCase
{

    public function testSmokeTest(): void
    {
        $userId = 123;
        $command = new SubscriptionList(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['user_id' => $userId]
        );
        $request = $command->getRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertRegExp('/^\/api\/v1\/users\/' . $userId . '\/subscriptions$/', $request->getUri()->getPath());
    }
}
