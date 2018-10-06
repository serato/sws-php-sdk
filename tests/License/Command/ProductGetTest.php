<?php
namespace Serato\SwsSdk\Test\License\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\License\Command\ProductGet;

class ProductGetTest extends AbstractTestCase
{
    public function testSmokeTest()
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
        $this->assertRegExp('/Basic/', $request->getHeaderLine('Authorization'));
        $this->assertRegExp('/' . $productId . '/', $request->getUri()->getPath());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMissingRequiredArg()
    {
        $command = new ProductGet(
            'app_id',
            'app_password',
            'http://my.server.com',
            []
        );

        $command->getRequest();
    }
}
