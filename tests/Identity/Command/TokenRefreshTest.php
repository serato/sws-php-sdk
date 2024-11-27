<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test\Identity\Command;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Identity\Command\TokenRefresh;
use GuzzleHttp\Psr7\Uri;

class TokenRefreshTest extends AbstractTestCase
{
    /**
     * @param array<mixed> $args
     * @return void
     *
     * @dataProvider missingRequiredArgProvider
     *
     *
     */
    public function testMissingRequiredArg(array $args): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $command = new TokenRefresh(
            'app_id',
            'app_password',
            'http://my.server.com',
            $args
        );

        $command->getRequest();
    }

    /**
     * @return array<array<mixed>>
     */
    public function missingRequiredArgProvider(): array
    {
        return [
            [[]],
            [['use_rotation' => true]]
        ];
    }

    public function testSmokeTest(): void
    {
        $refreshToken = 'refresh_token';

        $command = new TokenRefresh(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['refresh_token' => $refreshToken]
        );

        $request = $command->getRequest();
        parse_str((string)$request->getBody(), $bodyParams);
        $this->assertEquals('POST', $request->getMethod());
        $this->assertRegExp('/^\/api\/v2\/tokens\/refresh$/', $request->getUri()->getPath());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertEquals('application/x-www-form-urlencoded', $request->getHeaderLine('Content-Type'));
        $this->assertEquals($refreshToken, $bodyParams['refresh_token']);
    }

    /**
     * @param bool $useRotation
     * @param string $expectedUrlRegex
     * @return void
     * @dataProvider useTokenRotationProvider
     */
    public function testUseTokenRotationTest($useRotation, $expectedUrlRegex): void
    {
        $refreshToken = 'refresh_token';

        $command = new TokenRefresh(
            'app_id',
            'app_password',
            'http://my.server.com',
            ['refresh_token' => $refreshToken, 'use_rotation' => $useRotation]
        );

        $request = $command->getRequest();
        parse_str((string)$request->getBody(), $bodyParams);
        $this->assertEquals('POST', $request->getMethod());
        $this->assertRegExp($expectedUrlRegex, $request->getUri()->getPath());
        $this->assertRegExp('/^Basic [[:alnum:]=]+$/', $request->getHeaderLine('Authorization'));
        $this->assertEquals('application/x-www-form-urlencoded', $request->getHeaderLine('Content-Type'));
        $this->assertEquals($refreshToken, $bodyParams['refresh_token']);
        $this->assertEquals($useRotation, $bodyParams['use_rotation']);
    }

    /**
     * @return array<array<mixed>>
     */
    public function useTokenRotationProvider(): array
    {
        return [
            ['useRotation' => false, 'expectedUrlRegex' => '/^\/api\/v1\/tokens\/refresh$/' ],
            ['useRotation' => true, 'expectedUrlRegex' => '/^\/api\/v2\/tokens\/refresh$/' ],
        ];
    }
}
