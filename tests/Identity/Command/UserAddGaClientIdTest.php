<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Test\Identity\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Identity\Command\UserAddGaClientId;

class UserAddGaClientIdTest extends AbstractTestCase
{
    /**
     * @dataProvider missingRequiredArgProvider
     *
     * @expectedException \InvalidArgumentException
     */
    public function testMissingRequiredArg(array $args)
    {
        $command = new UserAddGaClientId(
            'app_id',
            'app_password',
            'http://my.server.com',
            $args
        );

        $command->getRequest();
    }

    public function missingRequiredArgProvider()
    {
        return [
            [[]],
            [['user_id' => 123]],
            [['ga_client_id' => '123abc456']]
        ];
    }

    public function testSmokeTest()
    {
        $user_id = 123;
        $ga_client_id = '123abc456';

        $command = new UserAddGaClientId(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['user_id' => $user_id, 'ga_client_id' => $ga_client_id]
        );

        $request = $command->getRequest();

        $this->assertEquals('POST', $request->getMethod());
        $this->assertRegExp('/Basic/', $request->getHeaderLine('Authorization'));
        $this->assertRegExp('/' . $user_id . '/', $request->getUri()->getPath());
        $this->assertRegExp('/application\/x\-www\-form\-urlencoded/', $request->getHeaderLine('Content-Type'));
        $this->assertRegExp('/ga_client_id/', (string)$request->getBody());
        $this->assertRegExp('/' . $ga_client_id . '/', (string)$request->getBody());
    }
}
