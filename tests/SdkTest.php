<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test;

use Serato\SwsSdk\Command;
use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Sdk;
use InvalidArgumentException;
use Serato\ServiceDiscovery\HostName;

class SdkTest extends AbstractTestCase
{
    /**
     * @param array<mixed> $args
     * @return void
     *
     * @dataProvider invalidConstructorOptionsProvider
     */
    public function testInvalidConstructorOptions(array $args, int $exceptionCode, string $assertText): void
    {
        try {
            $sdk = new Sdk($args, 'app_id', 'app_password');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals($e->getCode(), $exceptionCode, $assertText);
        }
    }

    /**
     * @return array<array<mixed>>
     */
    public function invalidConstructorOptionsProvider(): array
    {
        return [
            [[], 1005, 'No `env` or `base_uri` provided'],
            [['timeout' => 2], 1000, 'Non-float value for `timeout`'],
            [['timeout' => 2.0, 'env' => 'invalid'], 1001, 'Invalid `env` value'],
            [['base_uri' => ''], 1002, 'Non-array `base_uri` value'],
            [
                ['base_uri' => [
                        'id' => 'invalid value',
                        'license' => 'http://my.server',
                        'profile' => 'http://my.server',
                        'ecom' => 'http://my.server'
                    ]
                ],
                1003,
                'Invalid `base_uri` `id` value'
            ],
            [
                ['base_uri' => [
                        'id' => 'http://my.server',
                        'license' => 'invalid value',
                        'profile' => 'http://my.server',
                        'ecom' => 'http://my.server'
                   ]
                ],
                1003,
                'Invalid `base_uri` `license` value'
            ],
            [
                ['base_uri' => [
                        'id' => 'http://my.server',
                        'license' => 'http://my.server',
                        'profile' => 'invalid value',
                        'ecom' => 'http://my.server'
                    ]
                ],
                1003,
                'Invalid `base_uri` `profile` value'
            ],
            [
                ['base_uri' => [
                        'id' => 'http://my.server',
                        'license' => 'http://my.server',
                        'profile' => 'http://my.server',
                        'ecom' => 'invalid value'
                    ]
                ],
                1003,
                'Invalid `base_uri` `ecom` value'
            ],
            [
                ['env' => Sdk::ENV_PRODUCTION, 'handler' => 'not a callable'],
                1006,
                'Invalid `handler`, must be callable'
            ]
        ];
    }

    /**
     * @param array<mixed> $args
     * @dataProvider validConstructorOptionsProvider
     */
    public function testValidConstructorOptions(
        array $args,
        string $idServiceUri,
        string $licenseServiceUri,
        string $profileServiceUri,
        string $ecomServiceUri,
        string $daServiceUri,
        string $notificationsServiceUri,
        string $rewardsServiceUri,
        string $assertText,
        ?float $timeout = null,
        ?callable $handler = null
    ): void {
        $sdk = new Sdk($args, 'app_id', 'app_password');
        $config = $sdk->getConfig();

        $this->assertTrue(
            is_float($config['timeout']),
            $assertText . ' - Timeout is float'
        );
        if ($timeout !== null) {
            $this->assertEquals(
                $timeout,
                $config['timeout'],
                $assertText . ' - Custom timeout is correct'
            );
        }
        $this->assertTrue(
            is_array($config['base_uri']),
            $assertText . ' - `base_uri` option is array'
        );
        $this->assertEquals(
            $idServiceUri,
            $config['base_uri']['id'],
            $assertText . ' - `base_uri` `id` is correct'
        );
        $this->assertEquals(
            $licenseServiceUri,
            $config['base_uri']['license'],
            $assertText . ' - `base_uri` `license` is correct'
        );
        $this->assertEquals(
            $profileServiceUri,
            $config['base_uri']['profile'],
            $assertText . ' - `base_uri` `profile` is correct'
        );
        $this->assertEquals(
            $ecomServiceUri,
            $config['base_uri']['ecom'],
            $assertText . ' - `base_uri` `ecom` is correct'
        );
        $this->assertEquals(
            $daServiceUri,
            $config['base_uri']['da'],
            $assertText . ' - `base_uri` `da` is correct'
        );
        $this->assertEquals(
            $notificationsServiceUri,
            $config['base_uri']['notifications'],
            $assertText . ' - `base_uri` `notifications` is correct'
        );
        $this->assertEquals(
            $rewardsServiceUri,
            $config['base_uri']['rewards'],
            $assertText . ' - `base_uri` `rewards` is correct'
        );
        $this->assertEquals(
            $handler,
            $config['handler'],
            $assertText . ' - `handler` is correct'
        );
    }

    /**
     * @return array<array<mixed>>
     */
    public function validConstructorOptionsProvider(): array
    {
        $idServiceUri       = 'https://id.server.com';
        $licenseServiceUri  = 'https://license.server.io';
        $profileServiceUri  = 'https://profile.server.com';
        $ecomServiceUri     = 'https://ecom.server.com';
        $daServiceUri     = 'https://da.server.com';
        $notificationsServiceUri     = 'https://notifications.server.com';
        $rewardsServiceUri     = 'https://rewards.server.com';
        $handler            = function () {
        };

        return [
            [
                ['env' => Sdk::ENV_PRODUCTION],
                Sdk::BASE_URI_PRODUCTION_ID,
                Sdk::BASE_URI_PRODUCTION_LICENSE,
                Sdk::BASE_URI_PRODUCTION_PROFILE,
                Sdk::BASE_URI_PRODUCTION_ECOM,
                Sdk::BASE_URI_PRODUCTION_DA,
                Sdk::BASE_URI_PRODUCTION_NOTIFICATIONS,
                Sdk::BASE_URI_PRODUCTION_REWARDS,
                null,
                null,
                'Set `env` to ENV_PRODUCTION'
            ],
            [
                ['env' => Sdk::ENV_STAGING],
                Sdk::BASE_URI_STAGING_ID,
                Sdk::BASE_URI_STAGING_LICENSE,
                Sdk::BASE_URI_STAGING_PROFILE,
                Sdk::BASE_URI_STAGING_ECOM,
                Sdk::BASE_URI_STAGING_DA,
                Sdk::BASE_URI_STAGING_NOTIFICATIONS,
                Sdk::BASE_URI_STAGING_REWARDS,
                null,
                null,
                'Set `env` to ENV_STAGING'
            ],
            [
                ['env' => Sdk::ENV_PRODUCTION, 'timeout' => 1.222],
                Sdk::BASE_URI_PRODUCTION_ID,
                Sdk::BASE_URI_PRODUCTION_LICENSE,
                Sdk::BASE_URI_PRODUCTION_PROFILE,
                Sdk::BASE_URI_PRODUCTION_ECOM,
                Sdk::BASE_URI_PRODUCTION_DA,
                Sdk::BASE_URI_PRODUCTION_NOTIFICATIONS,
                Sdk::BASE_URI_PRODUCTION_REWARDS,
                1.222,
                null,
                'Set `env` to ENV_PRODUCTION and `timeout`'
            ],
            [
                ['env' => Sdk::ENV_STAGING, 'timeout' => 0.7622],
                Sdk::BASE_URI_STAGING_ID,
                Sdk::BASE_URI_STAGING_LICENSE,
                Sdk::BASE_URI_STAGING_PROFILE,
                Sdk::BASE_URI_STAGING_ECOM,
                Sdk::BASE_URI_STAGING_DA,
                Sdk::BASE_URI_STAGING_NOTIFICATIONS,
                Sdk::BASE_URI_STAGING_REWARDS,
                0.7622,
                null,
                'Set `env` to ENV_STAGING and `timeout`'
            ],
            [
                [
                    'base_uri' => [
                        'id' => $idServiceUri,
                        'license' => $licenseServiceUri,
                        'profile' => $profileServiceUri,
                        'ecom' => $ecomServiceUri,
                        'da'   => $daServiceUri,
                        'notifications'   => $notificationsServiceUri,
                        'rewards'   => $rewardsServiceUri
                    ],
                    'timeout' => 3.2
                ],
                $idServiceUri,
                $licenseServiceUri,
                $profileServiceUri,
                $ecomServiceUri,
                $daServiceUri,
                $notificationsServiceUri,
                $rewardsServiceUri,
                3.2,
                null,
                'Custom `base_uri` and `timeout`'
            ],
            [
                ['env' => Sdk::ENV_PRODUCTION, 'handler' => $handler],
                Sdk::BASE_URI_PRODUCTION_ID,
                Sdk::BASE_URI_PRODUCTION_LICENSE,
                Sdk::BASE_URI_PRODUCTION_PROFILE,
                Sdk::BASE_URI_PRODUCTION_ECOM,
                Sdk::BASE_URI_PRODUCTION_DA,
                Sdk::BASE_URI_PRODUCTION_NOTIFICATIONS,
                Sdk::BASE_URI_PRODUCTION_REWARDS,
                null,
                $handler,
                'Set `env` to ENV_PRODUCTION, custom `handler`'
            ]
        ];
    }

    /**
     * @return void
     *
     * @dataProvider hostNameArgumentsProvider
     */
    public function testCreateMethod(string $envName, int $envNumber): void
    {
        $appId = 'my-app-id';
        $appSecret = 'my-app-secret';
        $timeout = 3.33;
        $handler = function () {
            echo 'this is a function';
        };

        $hostName = new HostName($envName, $envNumber);
        $sdk = Sdk::create($hostName, $appId, $appSecret, $timeout, $handler);

        $config = $sdk->getConfig();

        $this->assertEquals($appId, $sdk->getAppId());
        $this->assertEquals($appSecret, $sdk->getAppPassword());
        $this->assertEquals($timeout, $config['timeout']);
        $this->assertEquals($handler, $config['handler']);

        $this->assertEquals($hostName->get(HostName::IDENTITY), $config['base_uri']['id']);
        $this->assertEquals($hostName->get(HostName::LICENSE), $config['base_uri']['license']);
        $this->assertEquals($hostName->get(HostName::PROFILE), $config['base_uri']['profile']);
        $this->assertEquals($hostName->get(HostName::ECOM), $config['base_uri']['ecom']);
        $this->assertEquals($hostName->get(HostName::DIGITAL_ASSETS), $config['base_uri']['da']);
        $this->assertEquals($hostName->get(HostName::NOTIFICATIONS), $config['base_uri']['notifications']);
        $this->assertEquals($hostName->get(HostName::REWARDS), $config['base_uri']['rewards']);
    }

    public function testCreateMethodCdnAuthArguments(): void
    {
        $timeout = 3.33;
        $handler = function () {
            echo 'this is a function';
        };
        $sdk = Sdk::create(
            new HostName('test', 8),
            'my-app-id',
            'my-app-secret',
            $timeout,
            $handler,
            'my-cdn-auth-id',
            'my-cdn-auth-secret'
        );

        // Instantiate (but don't execute) a request using the SDK
        $testRequest = $sdk->createIdentityClient()->getCommand('GetUser')->getRequest();

        $cdnAuthHeader = $testRequest->getHeaderLine(Command::CUSTOM_CDN_AUTH_HEADER);
        $decodedHeader = base64_decode($cdnAuthHeader);
        $this->assertNotFalse($decodedHeader, 'x-serato-cdn-auth header should be a base-64-encoded string');
        $this->assertEquals('my-cdn-auth-id:my-cdn-auth-secret', $decodedHeader);
    }

    public function testConstructorWithCdnAuthArguments(): void
    {
        $sdk = new Sdk(
            ['env' => Sdk::ENV_STAGING],
            'my-app-id',
            'my-app-secret',
            'my-cdn-auth-id',
            'my-cdn-auth-secret'
        );

        $testRequest = $sdk->createIdentityClient()->getCommand('GetUser')->getRequest();

        $cdnAuthHeader = $testRequest->getHeaderLine(Command::CUSTOM_CDN_AUTH_HEADER);
        $decodedHeader = base64_decode($cdnAuthHeader);
        $this->assertNotFalse($decodedHeader, 'x-serato-cdn-auth header should be a base-64-encoded string');
        $this->assertEquals('my-cdn-auth-id:my-cdn-auth-secret', $decodedHeader);
    }

    /**
     * @return array<array<mixed>>
     */
    public function hostNameArgumentsProvider(): array
    {
        return [
            ['production', 0],
            ['production', 1],
            ['production', 2],
            ['test', 0],
            ['test', 1],
            ['test', 2],
            ['test', 3],
            ['dev', 0],
            ['dev', 1],
            ['dev', 2]
        ];
    }
}
