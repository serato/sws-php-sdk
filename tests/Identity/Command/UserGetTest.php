<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Test\Identity\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Identity\Command\UserGet;

class UserGetTest extends AbstractTestCase
{
    public function testSmokeTest(): void
    {
        $command = new UserGet(
            'app_id',
            'app_password',
            'http://my.server.com'
        );

        $request = $command->getRequest('bearer_token_value');
        parse_str((string)$request->getBody(), $bodyParams);
        $this->assertEquals('GET', $request->getMethod());
        $this->assertRegExp('/^\/api\/v1\/me$/', $request->getUri()->getPath());
        $this->assertRegExp('/^Bearer [[:alnum:]=_]+$/', $request->getHeaderLine('Authorization'));
    }
}
