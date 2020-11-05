<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\Profile\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Profile\Command\UserGetBetaProgram;

class UserGetBetaProgramTest extends AbstractTestCase
{
    /**
     * @param array<mixed> $args
     * @return void
     *
     * @dataProvider missingRequiredArgProvider
     * @expectedException \InvalidArgumentException
     */
    public function testMissingRequiredArg(array $args): void
    {
        $command = new UserGetBetaProgram(
            'app_id',
            'app_password',
            'http://my.server.com',
            $args
        );

        $command->getRequest();
    }

    /**
     * @return array<array{'user_id': array<Integer>}>
     */
    public function missingRequiredArgProvider(): array
    {
        return [
            ['user_id' => [1234]]
        ];
    }

    public function testSmokeTest(): void
    {
        $userId = 123;

        $command = new UserGetBetaProgram(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['user_id'=>$userId]
        );

        $request = $command->getRequest();

        $this->assertEquals('GET', $request->getMethod());
        $this->assertRegExp('/Basic/', $request->getHeaderLine('Authorization'));
        $this->assertRegExp('/^\/api\/v[0-9]+\/users\/' . $userId . '\/betaprograms$/', $request->getUri()->getPath());
        $this->assertEquals('user_id=' . $userId, $request->getUri()->getQuery());
    }
}
