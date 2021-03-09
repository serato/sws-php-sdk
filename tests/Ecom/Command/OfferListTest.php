<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Test\Ecom\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Ecom\Command\OfferList;

class OfferListTest extends AbstractTestCase
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
        $command = new OfferList(
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
        $command = new OfferList(
            'app_id',
            'app_password',
            'http://my.server.com',
            [
                'product_type_id' => 136,
                'offer_type' => 'retention-offer'
            ]
        );
        $request = $command->getRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertStringEndsWith('/api/v1/offers/', $request->getUri()->getPath());
    }
}
