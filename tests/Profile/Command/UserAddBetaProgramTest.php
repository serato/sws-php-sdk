<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\Profile\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Profile\Command\UserAddBetaProgram;

class UserAddBetaProgramTest extends AbstractTestCase
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
        $command = new UserAddBetaProgram(
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
            ['beta_program_id' => ["beta_program_id"]]
        ];
    }

    public function testSmokeTest(): void
    {
        $userId = 123;
        $betaProgramId = "serato_studio_beta";
        $command = new UserAddBetaProgram(
            'app_id',
            'app_password',
            'http://my.server.com',
            [
                'user_id' => $userId ,
                'beta_program_id' => $betaProgramId
            ]
        );

        $request = $command->getRequest();

        $this->assertEquals('POST', $request->getMethod());
        $this->assertRegExp('/Basic/', $request->getHeaderLine('Authorization'));
        $this->assertRegExp('/application\/x\-www\-form\-urlencoded/', $request->getHeaderLine('Content-Type'));
        $this->assertRegExp('/^\/api\/v[0-9]+\/users\/' . $userId . '\/betaprograms$/', $request->getUri()->getPath());
    }
}
