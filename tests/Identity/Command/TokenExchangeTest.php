<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Test\Identity\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Identity\Command\TokenExchange;
use GuzzleHttp\Psr7\Uri;

class TokenExchangeTest extends AbstractTestCase
{
    /**
     * @dataProvider missingRequiredArgProvider
     *
     * @expectedException \InvalidArgumentException
     */
    public function testMissingRequiredArg(array $args): void
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

    public function testSmokeTest(): void
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
        parse_str((string)$request->getBody(), $bodyParams);
        $this->assertEquals('POST', $request->getMethod());
        $this->assertRegExp('/^\/api\/v1\/tokens\/exchange$/', $request->getUri()->getPath());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertEquals('application/x-www-form-urlencoded', $request->getHeaderLine('Content-Type'));
        $this->assertEquals($grantType, $bodyParams['grant_type']);
        $this->assertEquals($code, $bodyParams['code']);
        $this->assertEquals($redirectUri, $bodyParams['redirect_uri']);
    }
}
