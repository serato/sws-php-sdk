<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Test\Ecom\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Ecom\Command\SubscriptionList;

class SubscriptionListTest extends AbstractTestCase
{

    public function testSmokeTest()
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
        $this->assertContains('Basic', $request->getHeaderLine('Authorization'));
        $this->assertContains('/' . $userId . '/', $request->getUri()->getPath());
    }
}
