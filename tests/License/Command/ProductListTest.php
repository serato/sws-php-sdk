<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Test\License\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\License\Command\ProductList;

class ProductListTest extends AbstractTestCase
{
    public function testSmokeTest(): void
    {
        $orderId = 100000;

        $command = new ProductList(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['magento_order_id' => $orderId]
        );

        $request = $command->getRequest();
        parse_str((string)$request->getUri()->getQuery(), $queryParams);
        $this->assertEquals('GET', $request->getMethod());
        $this->assertRegExp('/^\/api\/v1\/products\/products$/', $request->getUri()->getPath());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertEquals($orderId, $queryParams['magento_order_id']);
    }
}
