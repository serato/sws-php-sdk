<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\Profile\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Profile\Command\UserAddBetaProgram;

class UserAddBetaProgramTest extends AbstractTestCase
{

    /**
     * @dataProvider missingRequiredArgProvider
     *
     * @expectedException \InvalidArgumentException
     */
    public function testMissingRequiredArg(array $args)
    {
        $command = new UserAddBetaProgram(
            'app_id',
            'app_password',
            'http://my.server.com',
            $args
        );

        $command->getRequest();
    }

    public function missingRequiredArgProvider()
    {
        return [
            ['beta_program_id' => ["beta_program_id"]]
        ];
    }

    public function testSmokeTest()
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
        $this->assertRegExp('/' . $userId . '/', $request->getUri()->getPath());
    }
}
