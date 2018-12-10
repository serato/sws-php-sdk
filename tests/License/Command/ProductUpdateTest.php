<?php
namespace Serato\SwsSdk\Test\License\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\License\Command\ProductUpdate;
use DateTime;

class ProductUpdateTest extends AbstractTestCase
{
    public function testSmokeTest()
    {
        $productId = '100-100';
        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', '2017-02-01 23:30:30');

        $command = new ProductUpdate(
            'app_id',
            'app_password',
            'http://my.server.com',
            [
                'product_id' => $productId, 
                'valid_to' => $dateTime,
                'subscription_status' => 'Past Due'
            ]
        );

        $request = $command->getRequest();

        $this->assertEquals('PUT', $request->getMethod());
        $this->assertRegExp('/Basic/', $request->getHeaderLine('Authorization'));
        $this->assertRegExp('/application\/x\-www\-form\-urlencoded/', $request->getHeaderLine('Content-Type'));
        $this->assertRegExp('/' . $productId . '/', $request->getUri()->getPath());
        $this->assertRegExp('/valid_to/', (string)$request->getBody());
        $this->assertRegExp('/2017\-02\-01/', (string)$request->getBody());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMissingRequiredArg()
    {
        $command = new ProductUpdate(
            'app_id',
            'app_password',
            'http://my.server.com',
            []
        );

        $command->getRequest();
    }
}
