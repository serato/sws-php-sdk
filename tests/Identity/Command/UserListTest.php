<?php
namespace Serato\SwsSdk\Test\Identity\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Identity\Command\UserList;

class UserListTest extends AbstractTestCase
{
    public function testSmokeTest()
    {
        $emailAddress   = 'emailadress';
        $gaClientId     = '123abc456def';

        $command = new UserList(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['email_address' => $emailAddress, 'ga_client_id' => $gaClientId]
        );

        $request = $command->getRequest();

        $this->assertEquals('GET', $request->getMethod());
        $this->assertRegExp('/Basic/', $request->getHeaderLine('Authorization'));
        $this->assertRegExp('/email_address/', $request->getUri()->getQuery());
        $this->assertRegExp('/' . $emailAddress . '/', $request->getUri()->getQuery());
        $this->assertRegExp('/ga_client_id/', $request->getUri()->getQuery());
        $this->assertRegExp('/' . $gaClientId . '/', $request->getUri()->getQuery());
    }
}
