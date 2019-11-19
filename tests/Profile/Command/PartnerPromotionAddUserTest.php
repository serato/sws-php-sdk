<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\Profile\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Profile\Command\PartnerPromotionAddUser;

class PartnerPromotionAddUserTest extends AbstractTestCase
{
    /**
     * @dataProvider missingRequiredArgProvider
     *
     * @expectedException \InvalidArgumentException
     */
    public function testMissingRequiredArg(array $args)
    {
        $command = new PartnerPromotionAddUser(
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
            [['user_id' => 1234]],
            [['promotion_name' => 'test-promo-name']]
        ];
    }
    public function testSmokeTest()
    {
        $userId = 123;

        $command = new PartnerPromotionAddUser(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['user_id' => $userId, 'promotion_name' => 'test-promo-name']
        );

        $request = $command->getRequest();

        $this->assertEquals('POST', $request->getMethod());
        $this->assertRegExp('/Basic/', $request->getHeaderLine('Authorization'));
        $this->assertRegExp('/^\/api\/v[0-9]+\/partnerpromotions\/code$/', $request->getUri()->getPath());
    }
}
