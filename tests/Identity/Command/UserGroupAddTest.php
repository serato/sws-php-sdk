<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\Identity\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Identity\Command\UserGroupAdd;

class UserGroupAddTest extends AbstractTestCase
{
    /**
     * @param array<mixed> $args
     * @return void
     *
     * @dataProvider missingRequiredArgProvider
     *
     *
     */
    public function testMissingRequiredArg(array $args): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $command = new UserGroupAdd(
            'app_id',
            'app_password',
            'http://my.server.com',
            $args
        );

        $command->getRequest();
    }

    /**
     * @return array<array<mixed>>
     */
    public function missingRequiredArgProvider(): array
    {
        return [
            ['group_name' => ["root"]]
        ];
    }

    public function testSmokeTest(): void
    {
        $userId = 123;
        $groupName = "root";

        $command = new UserGroupAdd(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['user_id' => $userId, 'group_name' => $groupName]
        );

        $request = $command->getRequest();
        parse_str((string)$request->getBody(), $bodyParams);
        $this->assertEquals('POST', $request->getMethod());
        $this->assertRegExp('/^\/api\/v1\/users\/' . $userId . '\/groups$/', $request->getUri()->getPath());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertEquals('application/x-www-form-urlencoded', $request->getHeaderLine('Content-Type'));
        $this->assertEquals($groupName, $bodyParams['group_name']);
    }
}
