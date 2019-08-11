<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\Profile\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Profile\Command\UserGetBetaProgram;

class UserGetBetaProgramTest extends AbstractTestCase
{
    /**
     * @dataProvider missingRequiredArgProvider
     *
     * @expectedException \InvalidArgumentException
     */
    public function testMissingRequiredArg(array $args)
    {
        $command = new UserGetBetaProgram(
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
            ['user_id' => [1234]]
        ];
    }
    public function testSmokeTest()
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
        $this->assertRegExp('/' . $userId . '/', $request->getUri()->getQuery());
        $this->assertRegExp('/user_id/', $request->getUri()->getQuery());
    }
}
