<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Test\License\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\License\Command\LicenseList;

class LicenseListTest extends AbstractTestCase
{
    public function testSmokeTest()
    {
        $userId = 123;

        $command = new LicenseList(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['user_id' => $userId]
        );

        $request = $command->getRequest();

        $this->assertEquals('GET', $request->getMethod());
        $this->assertRegExp('/Basic/', $request->getHeaderLine('Authorization'));
        $this->assertRegExp('/' . $userId . '/', $request->getUri()->getQuery());
        $this->assertRegExp('/user_id/', $request->getUri()->getQuery());
    }
}
