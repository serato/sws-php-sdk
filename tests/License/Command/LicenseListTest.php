<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\License\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\License\Command\LicenseList;

class LicenseListTest extends AbstractTestCase
{
    public function testSmokeTest(): void
    {
        $userId = 123;

        $command = new LicenseList(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['user_id' => $userId]
        );

        $request = $command->getRequest();
        parse_str((string)$request->getUri()->getQuery(), $queryParams);
        $this->assertEquals('GET', $request->getMethod());
        $this->assertRegExp('/^\/api\/v1\/users\/' . $userId . '\/licenses$/', $request->getUri()->getPath());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertEquals($userId, $queryParams['user_id']);
    }
}
