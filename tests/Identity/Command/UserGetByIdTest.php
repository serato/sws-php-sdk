<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Test\Identity\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Identity\Command\UserGetById;

class UserGetByIdTest extends AbstractTestCase
{
    public function testSmokeTest()
    {
        $userId = 123;
        $command = new UserGetById(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['user_id' => $userId]
        );

        $request = $command->getRequest();

        $request = $command->getRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertContains('Basic', $request->getHeaderLine('Authorization'));
        $this->assertContains('users/' . $userId, $request->getUri()->getPath());
    }
}
