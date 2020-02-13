<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Test\Ecom\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Ecom\Command\InvoiceCreate;

class InvoiceCreateTest extends AbstractTestCase
{
    public function missingRequiredArgProvider()
    {
        return [
            [[]],
            [['rubbish' => 'parameter']]
        ];
    }

    /**
     * Tests that an exception is thrown if required parameters are missing.
     * @dataProvider missingRequiredArgProvider
     *
     * @expectedException \InvalidArgumentException
     */
    public function testMissingRequiredArg(array $args): void
    {
        $command = new InvoiceCreate(
            'app_id',
            'app_password',
            'http://my.server.com',
            $args
        );
        $command->getRequest();
    }

    /**
     * Smoke test to verify that the command is creating a valid request.
     *
     * @return void
     */
    public function testSmokeTest(): void
    {
        $orderId = 123;
        $command = new InvoiceCreate(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['order_id' => $orderId]
        );

        $request = $command->getRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertStringEndsWith("/api/v1/orders/{$orderId}/invoice", $request->getUri()->getPath());
    }
}
