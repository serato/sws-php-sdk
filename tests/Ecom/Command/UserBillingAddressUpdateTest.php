<?php
namespace Serato\SwsSdk\Test\Ecom\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use InvalidArgumentException;
use Serato\SwsSdk\Ecom\Command\UserBillingAddressUpdate;

/**
 * Class UserBillingAddressUpdateTest
 * @package Serato\SwsSdk\Test\Ecom\Command
 */
class UserBillingAddressUpdateTest extends AbstractTestCase
{
    /**
     * @return array<int, array<int, array<string, string>>>
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
     * @param array<string, string> $args
     * @return void
     *
     * @dataProvider missingRequiredArgProvider
     */
    public function testMissingRequiredArg(array $args): void
    {
        $this->expectException(InvalidArgumentException::class);
        $command = new UserBillingAddressUpdate(
            'app_id',
            'app_password',
            'http://my.server.com',
            $args
        );
        $command->getRequest();
    }

    public function testSmokeTest(): void
    {
        $userId  = 1;
        $command = new UserBillingAddressUpdate(
            'app_id',
            'app_password',
            'http://my.server.com',
            [
                'user_id' => $userId,
            ]
        );

        $request = $command->getRequest();
        $this->assertEquals('PUT', $request->getMethod());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertStringEndsWith(
            "/api/v1/users/{$userId}/billingaddress",
            $request->getUri()->getPath()
        );
        $this->assertRegExp(
            "/^\/api\/v1\/users\/{$userId}\/billingaddress$/",
            $request->getUri()->getPath()
        );
    }
}
