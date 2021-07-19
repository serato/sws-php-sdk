<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\License\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\License\Command\ProductDelete;

class ProductDeleteTest extends AbstractTestCase
{
    public function testSmokeTest(): void
    {
        $productId = '100-100';

        $command = new ProductDelete(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['product_id' => $productId]
        );

        $request = $command->getRequest();
        parse_str((string)$request->getBody(), $bodyParams);
        $this->assertEquals('DELETE', $request->getMethod());
        $this->assertRegExp('/^\/api\/v1\/products\/products\/' . $productId . '$/', $request->getUri()->getPath());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMissingRequiredArg(): void
    {
        $command = new ProductDelete(
            'app_id',
            'app_password',
            'http://my.server.com',
            []
        );

        $command->getRequest();
    }
}
