<?php
namespace Serato\SwsSdk\Test\License\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\License\Command\ProductCreate;

class ProductCreateTest extends AbstractTestCase
{
    public function testSmokeTest()
    {
        $command = new ProductCreate(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['product_type_id' => 1],
            ['subscription_status' => 'Canceled']
        );

        $request = $command->getRequest();

        $this->assertEquals('POST', $request->getMethod());
        $this->assertRegExp('/Basic/', $request->getHeaderLine('Authorization'));
        $this->assertRegExp('/application\/x\-www\-form\-urlencoded/', $request->getHeaderLine('Content-Type'));
        $this->assertRegExp('/product_type_id/', (string)$request->getBody());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMissingRequiredArg()
    {
        $command = new ProductCreate(
            'app_id',
            'app_password',
            'http://my.server.com',
            []
        );

        $command->getRequest();
    }
}
