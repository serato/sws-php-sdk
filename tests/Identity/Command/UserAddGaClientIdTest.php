<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\Identity\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Identity\Command\UserAddGaClientId;

class UserAddGaClientIdTest extends AbstractTestCase
{
    /**
     * @param array<mixed> $args
     * @return void
     *
     * @dataProvider missingRequiredArgProvider
     *
     * @expectedException \InvalidArgumentException
     */
    public function testMissingRequiredArg(array $args): void
    {
        $command = new UserAddGaClientId(
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
            [[]],
            [['user_id' => 123]],
            [['ga_client_id' => '123abc456']]
        ];
    }

    public function testSmokeTest(): void
    {
        $userId = 123;
        $gaClientId = '123abc456';

        $command = new UserAddGaClientId(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['user_id' => $userId, 'ga_client_id' => $gaClientId]
        );

        $request = $command->getRequest();
        parse_str((string)$request->getBody(), $bodyParams);
        $this->assertEquals('POST', $request->getMethod());
        $this->assertRegExp('/^\/api\/v1\/users\/' . $userId . '\/gaclientid$/', $request->getUri()->getPath());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertEquals('application/x-www-form-urlencoded', $request->getHeaderLine('Content-Type'));
        $this->assertEquals($gaClientId, $bodyParams['ga_client_id']);
    }
}
