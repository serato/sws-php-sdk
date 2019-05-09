<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Test\Ecom\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Ecom\Command\SubscriptionCancel;

class SubscriptionCancelTest extends AbstractTestCase
{
    /**
     * Data provider
     */
    public function missingRequiredArgProvider()
    {
        return [
            [[]],
            [['user_id' => '123']],
            [['subscription_id' => 'sub-123']]
        ];
    }

    /**
     * @dataProvider missingRequiredArgProvider
     *
     * @expectedException \InvalidArgumentException
     */
    public function testMissingRequiredArg(array $args)
    {
        $command = new SubscriptionCancel(
            'app_id',
            'app_password',
            'http://my.server.com',
            $args
        );
        $command->getRequest();
    }

    public function testSmokeTest()
    {
        $subId = 'sub-123';
        $command = new SubscriptionCancel(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['user_id' => 123, 'subscription_id' => $subId]
        );

        $request = $command->getRequest();

        $this->assertEquals('DELETE', $request->getMethod());
        $this->assertContains('Basic', $request->getHeaderLine('Authorization'));
        $this->assertContains('/' . $subId, $request->getUri()->getPath());
    }
}
