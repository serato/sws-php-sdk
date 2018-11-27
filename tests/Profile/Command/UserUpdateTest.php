<?php
namespace Serato\SwsSdk\Test\Profile\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Profile\Command\UserUpdate;

class UserUpdateTest extends AbstractTestCase
{

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMissingRequiredArg()
    {
        $command = new UserUpdate(
            'app_id',
            'app_password',
            'http://my.server.com',
            []
        );

        $command->getRequest();
    }

    public function testSmokeTest()
    {
        $userId = 123;

        $command = new UserUpdate(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['user_id' => $userId]
        );

        $request = $command->getRequest();

        $this->assertEquals('PUT', $request->getMethod());
        $this->assertRegExp('/Bearer/', $request->getHeaderLine('Authorization'));
        $this->assertRegExp('/application\/x\-www\-form\-urlencoded/', $request->getHeaderLine('Content-Type'));
        $this->assertRegExp('/' . $userId . '/', $request->getUri()->getPath());
    }
}
