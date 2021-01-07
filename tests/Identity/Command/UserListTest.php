<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Test\Identity\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Identity\Command\UserList;

class UserListTest extends AbstractTestCase
{
    public function testSmokeTest(): void
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
        parse_str((string)$request->getUri()->getQuery(), $queryParams);
        $this->assertEquals('GET', $request->getMethod());
        $this->assertRegExp('/^\/api\/v1\/users$/', $request->getUri()->getPath());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertEquals($emailAddress, $queryParams['email_address']);
        $this->assertEquals($gaClientId, $queryParams['ga_client_id']);
    }
}
