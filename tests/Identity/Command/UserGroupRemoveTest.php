<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Test\Identity\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Identity\Command\UserGroupRemove;

class UserGroupRemoveTest extends AbstractTestCase
{
     /**
     * @expectedException \InvalidArgumentException
     */
    public function testMissingRequiredArg()
    {
        $command = new UserGroupRemove(
            'app_id',
            'app_password',
            'http://my.server.com',
            []
        );

        $command->getRequest();
    }

     /**
     * Test that missing user id
     * @expectedException \InvalidArgumentException
     */
    public function missingUserId()
    {
        $command = new UserGroupRemove(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['group_name' => "root"]
        );
    }

    public function testSmokeTest()
    {
        $userId = 1234;
        $groupName = "root";
        
        $command = new UserGroupRemove(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['user_id'=>$userId, 'group_name' => $groupName]
        );

        $request = $command->getRequest();
        parse_str((string)$request->getBody(), $bodyParams);
        $this->assertEquals('DELETE', $request->getMethod());
        $this->assertRegExp('/^\/api\/v1\/users\/' . $userId . '\/groups\/' . $groupName . '$/', $request->getUri()->getPath());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertEquals('application/x-www-form-urlencoded', $request->getHeaderLine('Content-Type'));
        $this->assertEquals($groupName, $bodyParams['group_name']);
    }
}
