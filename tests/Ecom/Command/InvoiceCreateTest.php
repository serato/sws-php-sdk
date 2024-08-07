<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\Ecom\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Ecom\Command\InvoiceCreate;
use DateTime;

class InvoiceCreateTest extends AbstractTestCase
{
    /**
     * @return array<array>
     */
    public function missingRequiredArgProvider(): array
    {
        return [
            [[]],
            [['rubbish' => 'parameter']]
        ];
    }

    /**
     * Tests that an exception is thrown if required parameters are missing.
     *
     * @param array<string, DateTime|int|string> $args
     *
     * @dataProvider missingRequiredArgProvider
     *
     */
    public function testMissingRequiredArg(array $args): void
    {
        $this->expectException(\InvalidArgumentException::class);
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

        $args = [
            'order_id' => $orderId,
            'transaction_reference' => 'mock_transaction_ref',
            'payment_instrument_transaction_reference' => 'mock_payment_instrument_transaction_ref'
        ];

        $command = new InvoiceCreate(
            'app_id',
            'app_password',
            'http://my.server.com',
            $args
        );

        $request = $command->getRequest();
        parse_str((string) $request->getBody(), $bodyParams);

        $this->assertEquals('POST', $request->getMethod());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertStringEndsWith("/api/v1/orders/{$orderId}/invoice", $request->getUri()->getPath());

        unset($args['order_id']);
        $expectedBodyParams = $args;
        $this->assertEquals($expectedBodyParams, $bodyParams);
    }
}
