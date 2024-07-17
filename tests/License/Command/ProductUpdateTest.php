<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\License\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\License\Command\ProductUpdate;
use DateTime;
use Exception;

class ProductUpdateTest extends AbstractTestCase
{
    public function testSmokeTest(): void
    {
        $productId = '100-100';
        $dateTime = new DateTime();

        $command = new ProductUpdate(
            'app_id',
            'app_password',
            'http://my.server.com',
            [
                'product_id' => $productId,
                'valid_to' => $dateTime,
                'subscription_status' => 'Past Due'
            ]
        );

        $request = $command->getRequest();
        parse_str((string)$request->getBody(), $bodyParams);
        $this->assertEquals('PUT', $request->getMethod());
        $this->assertRegExp('/^\/api\/v1\/products\/products\/' . $productId . '$/', $request->getUri()->getPath());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertEquals('application/x-www-form-urlencoded', $request->getHeaderLine('Content-Type'));
        $dateString = $dateTime->format(DateTime::RFC3339);
        $this->assertEquals($dateString, $bodyParams['valid_to']);
        $this->assertEquals('Past Due', $bodyParams['subscription_status']);
    }

    public function testMissingRequiredArg(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $command = new ProductUpdate(
            'app_id',
            'app_password',
            'http://my.server.com',
            []
        );

        $command->getRequest();
    }
}
