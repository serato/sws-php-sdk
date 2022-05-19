<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\Profile\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Profile\Command\UserUpdate;

class UserUpdateTest extends AbstractTestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMissingRequiredArg(): void
    {
        $command = new UserUpdate(
            'app_id',
            'app_password',
            'http://my.server.com',
            []
        );

        $command->getRequest();
    }

    public function testSmokeTest(): void
    {
        $userId = 123;
        $args   = [
            'user_id'               => $userId,
            'first_name'            => 'Andy',
            'last_name'             => 'Harris',
            'country_code'          => 'NZ',
            'region'                => 'Auckland',
            'postcode'              => '1010',
            'address_1'             => '80 Greys Avenue',
            'address_2'             => 'CBD',
            'city'                  => 'Auckland',
            'company'               => 'Serato',
            'locale'                => 'en',
            'global_contact_status' => 3,
            'twitch_access_token'   => 'gdfsgdfgsgdfgfgdsgfd',
        ];

        $command = new UserUpdate(
            'app_id',
            'app_password',
            'http://my.server.com',
            $args
        );

        $request = $command->getRequest();

        $this->assertEquals('PUT', $request->getMethod());
        $this->assertRegExp('/Basic/', $request->getHeaderLine('Authorization'));
        $this->assertRegExp('/application\/x\-www\-form\-urlencoded/', $request->getHeaderLine('Content-Type'));
        $this->assertRegExp('/^\/api\/v[0-9]+\/users\/' . $userId . '$/', $request->getUri()->getPath());

        unset($args['user_id']);
        $expectedBodyParams = $args;

        parse_str((string) $request->getBody(), $bodyParams);
        $this->assertEquals($expectedBodyParams, $bodyParams);
    }
}
