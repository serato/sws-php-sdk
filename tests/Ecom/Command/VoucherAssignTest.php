<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Test\Ecom\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Ecom\Command\VoucherAssign;
use DateTime;

class VoucherAssignTest extends AbstractTestCase
{
    /**
     * @return array<array>
     */
    public function missingRequiredArgProvider(): array
    {
        return [
            [[]],
            [['foo' => 'parameter']]
        ];
    }

     /**
     * Tests that an exception is thrown if required parameters are missing.
     *
     * @param array<string, DateTime|int|string> $args
     *
     * @dataProvider missingRequiredArgProvider
     * @expectedException \InvalidArgumentException
     */
    public function testMissingRequiredArg(array $args): void
    {
        $command = new VoucherAssign(
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
        $command = new VoucherAssign(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['user_id' => $userId, 'voucher_id' => 'voucherid']
        );

        $request = $command->getRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertStringEndsWith("/api/v1/users/{$userId}/vouchers", $request->getUri()->getPath());
    }
}
