<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Test\Profile\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Profile\Command\UserValidateAllBetaPrograms;

class UserValidateAllBetaProgramsTest extends AbstractTestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMissingRequiredArg()
    {
        $command = new UserValidateAllBetaPrograms(
            'app_id',
            'app_password',
            'http://my.server.com',
            []
        );

        $command->getRequest();
    }

    public function testSmokeTest()
    {
        $user_id = 123;

        $command = new UserValidateAllBetaPrograms(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['user_id' => $user_id]
        );

        $request = $command->getRequest();

        $this->assertEquals('POST', $request->getMethod());
        $this->assertRegExp('/Basic/', $request->getHeaderLine('Authorization'));
        $this->assertRegExp('/^\/api\/v[0-9]+\/users\/' . $user_id . '\/betaprograms\/validateall$/', $request->getUri()->getPath());
    }
}
