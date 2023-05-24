<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\License\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\License\Command\ProductTypeGet;

class ProductTypeGetTest extends AbstractTestCase
{
    public function testSmokeTest(): void
    {
        $productTypeId = 100;
        $command = new ProductTypeGet(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['product_type_id' => $productTypeId]
        );

        $request = $command->getRequest('bearer_token_value');
        parse_str((string)$request->getBody(), $bodyParams);
        $this->assertEquals('GET', $request->getMethod());
        $this->assertRegExp('/^\/api\/v1\/products\/types\/' . $productTypeId . '$/', $request->getUri()->getPath());
        $this->assertRegExp('/^Bearer [[:alnum:]=_]+$/', $request->getHeaderLine('Authorization'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMissingRequiredArg(): void
    {
        $command = new ProductTypeGet(
            'app_id',
            'app_password',
            'http://my.server.com',
            []
        );

        $command->getRequest();
    }
}
