<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\Ecom\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Ecom\Command\InvoicesSummary;

class InvoicesSummaryTest extends AbstractTestCase
{
    /**
     * Smoke test to verify that the command is creating a valid request.
     *
     * @return void
     */
    public function testSmokeTest(): void
    {
        $command = new InvoicesSummary(
            'app_id',
            'app_password',
            'http://my.server.com',
            [
                'date' => '2020-03-19',
            ]
        );
        $request = $command->getRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertStringEndsWith('/api/v1/invoices/summary', $request->getUri()->getPath());
    }
}
