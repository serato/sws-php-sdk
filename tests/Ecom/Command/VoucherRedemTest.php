<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\Ecom\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Ecom\Command\VoucherRedeem;
use DateTime;
use InvalidArgumentException;

/**
 * Class VoucherRedemTest
 * @package Serato\SwsSdk\Test\Ecom\Command
 */
class VoucherRedemTest extends AbstractTestCase
{
    /**
     * @return array<array>
     */
    public function missingRequiredArgProvider(): array
    {
        return [
            [
                []
            ],
            [
                [
                    'foo' => 'parameter'
                ]
            ],
            [
                [
                    'user_id' => 123,
                ]
            ],
            [
                [
                    'voucher_id' => 'test',
                ]
            ],
            [
                [
                    'user_id' => 'invalid_id',
                    'voucher_id' => 6456,
                ]
            ],
        ];
    }

     /**
     * Tests that an exception is thrown if required parameters are missing.
     *
     * @param array<string, DateTime|int|string> $args
     *
     * @dataProvider missingRequiredArgProvider
     */
    public function testMissingRequiredArg(array $args): void
    {
        $this->expectException(InvalidArgumentException::class);
        $command = new VoucherRedeem(
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
        $userId = 123;
        $voucherId = 'voucherid';
        $command = new VoucherRedeem(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['user_id' => $userId, 'voucher_id' => 'voucherid']
        );

        $request = $command->getRequest();
        $this->assertEquals('PUT', $request->getMethod());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertStringEndsWith("/api/v1/users/{$userId}/vouchers/{$voucherId}", $request->getUri()->getPath());
    }
}
