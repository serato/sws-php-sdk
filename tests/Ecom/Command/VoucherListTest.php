<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\Ecom\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Ecom\Command\VoucherList;

class VoucherListTest extends AbstractTestCase
{
    public function testSmokeTest(): void
    {
        $userId = 123;

        $command = new VoucherList(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['user_id' => $userId]
        );

        $request = $command->getRequest();
        parse_str((string)$request->getUri()->getQuery(), $queryParams);
        $this->assertEquals('GET', $request->getMethod());
        $this->assertStringEndsWith("/api/v1/users/{$userId}/vouchers", $request->getUri()->getPath());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertEquals($userId, $queryParams['user_id']);
    }
}
