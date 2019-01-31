<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Test\License\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\License\Command\ProductList;

class ProductListTest extends AbstractTestCase
{
    public function testSmokeTest()
    {
        $orderId = 100000;

        $command = new ProductList(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['magento_order_id' => $orderId]
        );

        $request = $command->getRequest();

        $this->assertEquals('GET', $request->getMethod());
        $this->assertRegExp('/Basic/', $request->getHeaderLine('Authorization'));
        $this->assertRegExp('/magento_order_id/', $request->getUri()->getQuery());
        $this->assertRegExp('/' . $orderId . '/', $request->getUri()->getQuery());
    }
}
