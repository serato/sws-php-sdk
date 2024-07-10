<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\Profile\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Profile\Command\UserGet;

class UserGetTest extends AbstractTestCase
{
    public function testMissingRequiredArg(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $command = new UserGet(
            'app_id',
            'app_password',
            'http://my.server.com',
            []
        );

        $command->getRequest();
    }

    public function testSmokeTest(): void
    {
        $user_id = 123;

        $command = new UserGet(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['user_id' => $user_id]
        );

        $request = $command->getRequest();

        $this->assertEquals('GET', $request->getMethod());
        $this->assertRegExp('/Basic/', $request->getHeaderLine('Authorization'));
        $this->assertRegExp('/^\/api\/v[0-9]+\/users\/' . $user_id . '$/', $request->getUri()->getPath());
    }
}
