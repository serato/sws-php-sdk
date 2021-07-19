<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\Ecom\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Ecom\Command\VoucherTypeList;

class VoucherTypeListTest extends AbstractTestCase
{
    /**
     * Smoke test to verify that the command is creating a valid request.
     *
     * @return void
     */
    public function testSmokeTest(): void
    {
        $command = new VoucherTypeList(
            'app_id',
            'app_password',
            'http://my.server.com',
            [
                'product_type_id' => 136,
                'voucher_type' => 'retention-offer'
            ]
        );
        $request = $command->getRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertStringEndsWith('/api/v1/vouchers/types', $request->getUri()->getPath());
    }
}
