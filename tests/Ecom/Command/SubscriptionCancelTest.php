<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\Ecom\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Ecom\Command\SubscriptionCancel;
use DateTime;

class SubscriptionCancelTest extends AbstractTestCase
{
    /**
     * Data provider
     *
     * @return array<array>
     */
    public function missingRequiredArgProvider(): array
    {
        return [
            [[]],
            [['user_id' => '123']],
            [['subscription_id' => 'sub-123']]
        ];
    }

    /**
     * @param array<string, DateTime|int|string> $args
     *
     * @dataProvider missingRequiredArgProvider
     *
     */
    public function testMissingRequiredArg(array $args): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $command = new SubscriptionCancel(
            'app_id',
            'app_password',
            'http://my.server.com',
            $args
        );
        $command->getRequest();
    }

    public function testSmokeTest(): void
    {
        $subId = 'sub-123';
        $userId = 123;
        $command = new SubscriptionCancel(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['user_id' => $userId, 'subscription_id' => $subId]
        );

        $request = $command->getRequest();
        $this->assertEquals('DELETE', $request->getMethod());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertRegExp("/^\/api\/v1\/users\/{$userId}\/subscriptions\/{$subId}$/", $request->getUri()->getPath());
    }
}
