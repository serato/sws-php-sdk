<?php
namespace Serato\SwsSdk\Test\Identity\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Identity\Command\UserGet;

class UserGetTest extends AbstractTestCase
{
    public function testSmokeTest()
    {
        $command = new UserGet(
            'app_id',
            'app_password',
            'http://my.server.com'
        );

        $request = $command->getRequest('bearer_token_value');

        $this->assertEquals('GET', $request->getMethod());
        $this->assertRegExp('/Bearer/', $request->getHeaderLine('Authorization'));
        $this->assertRegExp('/bearer_token_value/', $request->getHeaderLine('Authorization'));
    }
}
