<?php
namespace Serato\SwsSdk\Test\Ecom\Command;

use Serato\SwsSdk\Ecom\Command\UpdateCartBillingAddress;
use Serato\SwsSdk\Test\AbstractTestCase;
use InvalidArgumentException;

/**
 * Class SubscriptionBillingAddressUpdateTest
 * @package Serato\SwsSdk\Test\Ecom\Command
 */
class SubscriptionBillingAddressUpdateTest extends AbstractTestCase
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
        $command = new UpdateCartBillingAddress(
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
        $command = new UpdateCartBillingAddress(
            'app_id',
            'app_password',
            'http://my.server.com',
            [
                'user_id' => $userId,
            ]
        );

        $request = $command->getRequest();
        parse_str((string) $request->getBody(), $bodyParams);

        $this->assertEquals('PUT', $request->getMethod());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertStringEndsWith("/api/v1/users/{$userId}/subscriptions/billingaddress", $request->getUri()->getPath());
        $this->assertEquals('application/x-www-form-urlencoded', $request->getHeaderLine('Content-Type'));
    }
}
