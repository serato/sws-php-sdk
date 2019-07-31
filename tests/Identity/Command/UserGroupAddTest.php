<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Test\Identity\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Identity\Command\UserGroupAdd;

class UserGroupAddTest extends AbstractTestCase
{
     /**
     * @dataProvider missingRequiredArgProvider
     *
     * @expectedException \InvalidArgumentException
     */
    public function testMissingRequiredArg(array $args)
    {
        $command = new UserGroupAdd(
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
            ['group_name' => ["root"]]
        ];
    }

    /**
     * Test that missing user id
     * @expectedException \InvalidArgumentException
     */
    public function missingUserId()
    {
        $command = new UserGroupAdd(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['group_name' => "root"]
        );
    }

    public function testSmokeTest()
    {
        $userId = 123;
        
        $command = new UserGroupAdd(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['user_id' => $userId, 'group_name' => "root"]
        );

        $request = $command->getRequest();

        $this->assertEquals('POST', $request->getMethod());
        $this->assertRegExp('/Basic/', $request->getHeaderLine('Authorization'));
        $this->assertRegExp('/groups/', $request->getUri()->getPath());
        $this->assertRegExp('/application\/x\-www\-form\-urlencoded/', $request->getHeaderLine('Content-Type'));
    }
}
