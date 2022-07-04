<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\Rewards\Command;

use Serato\SwsSdk\Rewards\Command\RefereeActivityList;
use Serato\SwsSdk\Test\AbstractTestCase;

/**
 * Class RefereeActivityListTest
 * @package Serato\SwsSdk\Test\Rewards\Command
 */
class RefereeActivityListTest extends AbstractTestCase
{
    public function testSmokeTest(): void
    {
        $command = new RefereeActivityList(
            'app_id',
            'app_password',
            'http://my.server.com',
            [
                'uid' => 121121212,
                'code' => 'asdasd7s676'
            ]
        );
        $request = $command->getRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertRegExp('/^\/api\/v1\/referralcode\/asdasd7s676\/referee\/121121212/', $request->getUri()->getPath(
        ));
    }
}
