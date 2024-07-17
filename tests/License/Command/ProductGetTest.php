<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\License\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\License\Command\ProductGet;

class ProductGetTest extends AbstractTestCase
{
    public function testSmokeTest(): void
    {
        $productId = '100-100';

        $command = new ProductGet(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['product_id' => $productId]
        );

        $request = $command->getRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertRegExp('/^\/api\/v1\/products\/products\/' . $productId . '$/', $request->getUri()->getPath());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
    }

    public function testMissingRequiredArg(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $command = new ProductGet(
            'app_id',
            'app_password',
            'http://my.server.com',
            []
        );

        $command->getRequest();
    }
}
