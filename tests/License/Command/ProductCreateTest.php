<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\License\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\License\Command\ProductCreate;

class ProductCreateTest extends AbstractTestCase
{
    public function testSmokeTest(): void
    {
        $command = new ProductCreate(
            'app_id',
            'app_password',
            'http://my.server.com',
            [
                'product_type_id' => 1,
                'subscription_status' => 'Canceled'
            ]
        );

        $request = $command->getRequest();
        parse_str((string)$request->getBody(), $bodyParams);
        $this->assertEquals('POST', $request->getMethod());
        $this->assertRegExp('/^\/api\/v1\/products\/products$/', $request->getUri()->getPath());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertEquals('application/x-www-form-urlencoded', $request->getHeaderLine('Content-Type'));
        $this->assertEquals(1, $bodyParams['product_type_id']);
        $this->assertEquals('Canceled', $bodyParams['subscription_status']);
    }

    public function testMissingRequiredArg(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $command = new ProductCreate(
            'app_id',
            'app_password',
            'http://my.server.com',
            []
        );

        $command->getRequest();
    }
}
