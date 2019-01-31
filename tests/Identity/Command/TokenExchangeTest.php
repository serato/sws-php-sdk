<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Test\Identity\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Identity\Command\TokenExchange;

class TokenExchangeTest extends AbstractTestCase
{
    /**
     * @dataProvider missingRequiredArgProvider
     *
     * @expectedException \InvalidArgumentException
     */
    public function testMissingRequiredArg(array $args)
    {
        $command = new TokenExchange(
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
            [['code' => 'my_code', 'redirect_uri' => 'my://uri']],
            [['grant_type' => 'my_grant_type', 'redirect_uri' => 'my://uri']],
            [['grant_type' => 'my_grant_type', 'code' => 'my_code']]
        ];
    }

    public function testSmokeTest()
    {
        $grantType      = 'my_grant_type';
        $code           = '123abc456';
        $redirectUri    = 'myuri';

        $command = new TokenExchange(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['grant_type' => $grantType, 'code' => $code, 'redirect_uri' => $redirectUri]
        );

        $request = $command->getRequest();

        $this->assertEquals('POST', $request->getMethod());
        $this->assertRegExp('/Basic/', $request->getHeaderLine('Authorization'));
        $this->assertRegExp('/application\/x\-www\-form\-urlencoded/', $request->getHeaderLine('Content-Type'));

        $this->assertRegExp('/grant_type/', (string)$request->getBody());
        $this->assertRegExp('/' . $grantType . '/', (string)$request->getBody());

        $this->assertRegExp('/code/', (string)$request->getBody());
        $this->assertRegExp('/' . $code . '/', (string)$request->getBody());

        $this->assertRegExp('/redirect_uri/', (string)$request->getBody());
        $this->assertRegExp('/' . $redirectUri . '/', (string)$request->getBody());
    }
}
