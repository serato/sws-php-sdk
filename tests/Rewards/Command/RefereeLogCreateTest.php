<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\Rewards\Command;

use Serato\SwsSdk\Rewards\Command\ReferralLogCreate;
use Serato\SwsSdk\Test\AbstractTestCase;

/**
 * Class RefereeLogCreateTest
 * @package Serato\SwsSdk\Test\Rewards\Command
 */
class RefereeLogCreateTest extends AbstractTestCase
{
    /**
     * Smoke test if the requests are generated correctly.
     */
    public function testSmokeTest(): void
    {
        $command = new ReferralLogCreate(
            'app_id',
            'app_password',
            'http://my.server.com',
            [
                'code' => 'sdfsdfsdfsd',
                'referrer_user_id' => 3423432,
                'voucher_id' => 'ewrwerwerwerwe',
                'voucher_type_id' => 324234,
                'voucher_batch_id' => 'RF35S'
            ]
        );
        $request = $command->getRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertRegExp('/^\/api\/v1\/referralcode\/sdfsdfsdfsd\/log/', $request->getUri()->getPath());
    }

    /**
     * Exception to be thrown if `code` is missing from the data.
     */
    public function testMissingRequiredCode(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $command = new ReferralLogCreate(
            'app_id',
            'app_password',
            'http://my.server.com',
            [
                'referrer_user_id' => 3423432,
                'voucher_id' => 'ewrwerwerwerwe'
            ]
        );
        $command->getRequest();
    }
}
