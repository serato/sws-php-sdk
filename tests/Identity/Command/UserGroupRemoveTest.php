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

    public function testSmokeTest()
    {
        $group_name = "root";
        
        $command = new UserGroupRemove(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['group_name' => $group_name]
        );

        $request = $command->getRequest();

        $this->assertEquals('DELETE', $request->getMethod());
        $this->assertRegExp('/Basic/', $request->getHeaderLine('Authorization'));
        $this->assertRegExp("/${group_name}/", $request->getUri()->getPath());
        $this->assertRegExp('/application\/x\-www\-form\-urlencoded/', $request->getHeaderLine('Content-Type'));
    }
}