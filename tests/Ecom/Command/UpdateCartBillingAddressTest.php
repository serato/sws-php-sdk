<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\Ecom\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Ecom\Command\UpdateCartBillingAddress;
use DateTime;

/**
 * Class UpdateCartBillingAddressTest
 * @package Serato\SwsSdk\Test\Ecom\Command
 */
class UpdateCartBillingAddressTest extends AbstractTestCase
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
     * @expectedException \InvalidArgumentException
     */
    public function testMissingRequiredArg(array $args): void
    {
        $command = new UpdateCartBillingAddress(
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
        $cartUuid = 'testUuid';
        $args     = [
            'cart_uuid'    => $cartUuid,
            'first_name'   => 'John',
            'last_name'    => 'Doe',
            'company'      => 'Serato',
            'address1'     => 'Level 2',
            'address2'     => '80 Greys Avenue',
            'city'         => 'Auckland',
            'state'        => '',
            'zip'          => '1010',
            'country_code' => 'NZ',
        ];
        $command  = new UpdateCartBillingAddress(
            'app_id',
            'app_password',
            'http://my.server.com',
            $args
        );

        $request = $command->getRequest();
        parse_str((string) $request->getBody(), $bodyParams);

        $this->assertEquals('PUT', $request->getMethod());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertStringEndsWith("/api/v1/carts/{$cartUuid}/billingaddress", $request->getUri()->getPath());
        $this->assertEquals('application/x-www-form-urlencoded', $request->getHeaderLine('Content-Type'));

        unset($args['cart_uuid']);
        $expectedBodyParams = $args;

        $this->assertEquals($expectedBodyParams, $bodyParams);
    }
}
