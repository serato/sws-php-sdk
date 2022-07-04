<?php

declare(strict_types=1);

namespace Serato\SwsSdk;

use Serato\SwsSdk\Client;
use Serato\SwsSdk\Da\DaClient;
use Serato\SwsSdk\Ecom\EcomClient;
use Serato\SwsSdk\License\LicenseClient;
use Serato\SwsSdk\Notifications\NotificationsClient;
use Serato\SwsSdk\Profile\ProfileClient;
use Serato\SwsSdk\Identity\IdentityClient;
use Serato\ServiceDiscovery\HostName;
use InvalidArgumentException;
use Serato\SwsSdk\Rewards\RewardsClient;

/**
 * Builds SWS clients based on configuration settings.
 */
class Sdk
{
    public const BASE_URI                       = 'base_uri';
    public const BASE_URI_ID                    = 'id';
    public const BASE_URI_LICENSE               = 'license';
    public const BASE_URI_PROFILE               = 'profile';
    public const BASE_URI_ECOM                  = 'ecom';
    public const BASE_URI_DA                    = 'da';
    public const BASE_URI_NOTIFICATIONS         = 'notifications';
    public const BASE_URI_REWARDS              = 'rewards';


    public const ENV_PRODUCTION                = 'production';
    public const ENV_STAGING                   = 'staging';

    public const BASE_URI_STAGING_ID           = 'https://id.serato.xyz';
    public const BASE_URI_STAGING_LICENSE      = 'https://license.serato.xyz';
    public const BASE_URI_STAGING_PROFILE      = 'https://profile.serato.xyz';
    public const BASE_URI_STAGING_ECOM         = 'https://ecom.serato.xyz';
    public const BASE_URI_STAGING_DA           = 'https://da.serato.xyz';
    public const BASE_URI_STAGING_NOTIFICATIONS  = 'https://notifications.serato.xyz';
    public const BASE_URI_STAGING_REWARDS  = 'https://rewards.serato.xyz';

    public const BASE_URI_PRODUCTION_ID            = 'https://id.serato.com';
    public const BASE_URI_PRODUCTION_LICENSE       = 'https://license.serato.com';
    public const BASE_URI_PRODUCTION_PROFILE       = 'https://profile.serato.com';
    public const BASE_URI_PRODUCTION_ECOM          = 'https://ecom.serato.com';
    public const BASE_URI_PRODUCTION_DA            = 'https://da.serato.com';
    public const BASE_URI_PRODUCTION_NOTIFICATIONS = 'https://notifications.serato.com';
    public const BASE_URI_PRODUCTION_REWARDS = 'https://rewards.serato.com';


    /**
     * Client application ID
     *
     * @var string
     */
    private $appId;

    /**
     * Client application password
     *
     * @var string
     */
    private $appPassword;

    /**
     * Client configuration data
     *
     * @var array{'base_uri': array<String, String>, 'timeout': ?float, 'handler': ?callable}
     */
    private $config = [
        'base_uri' => [],
        'timeout' => 2.0,
        'handler' => null
    ];

    /**
     * The `$args` parameter accepts the following options:
     *
     * - `env`: (string) The SWS environment that the SDK interacts with. Accepted
     *   values are `production` and `staging`. One of `env` or `base_uri` must
     *   be specified.
     * - `base_uri`: (array) An array of base URIs for each SWS service. The array
     *   must contains key named `id`, `license`, `profile` and `ecom` and provide a complete base URI
     *   including protocol (http or https) for each. One of `env` or `base_uri`
     *   must be specified.
     * - `timeout`: (float) The default request timeout, in seconds.
     * - `handler`: (callable) Function that transfers HTTP requests over the
     *   wire. Passed to Guzzle clients to override the default handler
     *   (eg. to use a mock handler). See the Guzzle docs for more info.
     *
     * @link http://guzzle.readthedocs.io/en/latest/quickstart.html Guzzle documentation
     *
     * @param array<String, mixed> $args Client configuration arguments
     * @param string    $appId          Client application ID
     * @param string    $appPassword    Client application password
     *
     * @return void
     *
     * @throws InvalidArgumentException If any required options are missing or
     *                                   an invalid value is provided.
     */
    final public function __construct(array $args, string $appId = '', string $appPassword = '')
    {
        $this->appId = $appId;
        $this->appPassword = $appPassword;

        if (isset($args['timeout'])) {
            if (!is_float($args['timeout'])) {
                throw new InvalidArgumentException(
                    'The `timeout` config option value must be a float',
                    1000
                );
            } else {
                $this->config['timeout'] = $args['timeout'];
            }
        }

        if (isset($args['handler'])) {
            if (!is_callable($args['handler'])) {
                throw new InvalidArgumentException(
                    'The `handler` config option value must be a callable',
                    1006
                );
            } else {
                $this->config['handler'] = $args['handler'];
            }
        }

        if (isset($args['env'])) {
            if (in_array($args['env'], [self::ENV_STAGING, self::ENV_PRODUCTION])) {
                if ($args['env'] == self::ENV_STAGING) {
                    $this->setBaseUriConfig(
                        self::BASE_URI_STAGING_ID,
                        self::BASE_URI_STAGING_LICENSE,
                        self::BASE_URI_STAGING_PROFILE,
                        self::BASE_URI_STAGING_ECOM,
                        self::BASE_URI_STAGING_DA,
                        self::BASE_URI_STAGING_NOTIFICATIONS,
                        self::BASE_URI_STAGING_REWARDS
                    );
                }
                if ($args['env'] == self::ENV_PRODUCTION) {
                    $this->setBaseUriConfig(
                        self::BASE_URI_PRODUCTION_ID,
                        self::BASE_URI_PRODUCTION_LICENSE,
                        self::BASE_URI_PRODUCTION_PROFILE,
                        self::BASE_URI_PRODUCTION_ECOM,
                        self::BASE_URI_PRODUCTION_DA,
                        self::BASE_URI_PRODUCTION_NOTIFICATIONS,
                        self::BASE_URI_PRODUCTION_REWARDS
                    );
                }
            } else {
                throw new InvalidArgumentException(
                    'The `env` config option value must be one of `' . self::ENV_STAGING .
                    '` or `' . self::ENV_PRODUCTION . '`.',
                    1001
                );
            }
        }

        if (isset($args[self::BASE_URI])) {
            if (!is_array($args[self::BASE_URI])) {
                throw new InvalidArgumentException(
                    'The `base_uri` config option value must be an array containing ' .
                    '`id`, `license`, `profile` and `ecom` keys.',
                    1002
                );
            } else {
                # Validate each item in $args[self::BASE_URI] such that:
                # - It exists. If it doesn't use a default value for the production environment
                # - It contains a valid network protocol (ie. http or https)

                # Default to production endpoints
                $services = [
                    self::BASE_URI_ID               => self::BASE_URI_PRODUCTION_ID,
                    self::BASE_URI_LICENSE          => self::BASE_URI_PRODUCTION_LICENSE,
                    self::BASE_URI_PROFILE          => self::BASE_URI_PRODUCTION_PROFILE,
                    self::BASE_URI_ECOM             => self::BASE_URI_PRODUCTION_ECOM,
                    self::BASE_URI_DA               => self::BASE_URI_PRODUCTION_DA,
                    self::BASE_URI_NOTIFICATIONS    => self::BASE_URI_PRODUCTION_NOTIFICATIONS,
                    self::BASE_URI_REWARDS          => self::BASE_URI_PRODUCTION_REWARDS
                ];

                foreach ($services as $name => $defaultUri) {
                    if (isset($args[self::BASE_URI][$name])) {
                        if (
                            strpos($args[self::BASE_URI][$name], 'http://') !== 0 &&
                            strpos($args[self::BASE_URI][$name], 'https://') !== 0
                        ) {
                            throw new InvalidArgumentException(
                                'The `' . self::BASE_URI . '` `' . $name .
                                '` config option value must including a valid network protocol (ie. http or https)',
                                1003
                            );
                        }
                        $services[$name] = $args[self::BASE_URI][$name];
                    }
                }

                $this->setBaseUriConfig(
                    $services[self::BASE_URI_ID],
                    $services[self::BASE_URI_LICENSE],
                    $services[self::BASE_URI_PROFILE],
                    $services[self::BASE_URI_ECOM],
                    $services[self::BASE_URI_DA],
                    $services[self::BASE_URI_NOTIFICATIONS],
                    $services[self::BASE_URI_REWARDS]
                );
            }
        }

        if (count($this->config[self::BASE_URI]) === 0) {
            throw new InvalidArgumentException(
                'You must specify one of either the `env` or `base_uri` config ' .
                'option values.',
                1005
            );
        }
    }

    /**
     * Creates an Sdk instance
     *
     * @param HostName $hostName            `Serato\ServiceDiscovery\HostName` instance.
     * @param string $appId                 Client application ID.
     * @param string $appPassword           Client application password.
     * @param float|null $timeout           Request timeout (in seconds). Default is 2.0.
     * @param callable|null $guzzleHander   Function that transfers HTTP requests over the wire. Passed to Guzzle
     *                                      clients to override the default handler (eg. to use a mock handler).
     *                                      See the Guzzle docs for more info.
     *
     * @link http://guzzle.readthedocs.io/en/latest/quickstart.html Guzzle documentation
     *
     * @return self
     */
    final public static function create(
        HostName $hostName,
        string $appId,
        string $appPassword,
        ?float $timeout = null,
        ?callable $guzzleHander = null
    ): self {
        $args = [
            self::BASE_URI => [
                self::BASE_URI_ID               => $hostName->get(HostName::IDENTITY),
                self::BASE_URI_LICENSE          => $hostName->get(HostName::LICENSE),
                self::BASE_URI_PROFILE          => $hostName->get(HostName::PROFILE),
                self::BASE_URI_ECOM             => $hostName->get(HostName::ECOM),
                self::BASE_URI_DA               => $hostName->get(HostName::DIGITAL_ASSETS),
                self::BASE_URI_NOTIFICATIONS    => $hostName->get(HostName::NOTIFICATIONS),
                self::BASE_URI_REWARDS    => $hostName->get(HostName::REWARDS)
            ]
        ];
        if ($timeout !== null) {
            $args['timeout'] = $timeout;
        }
        if ($guzzleHander !== null) {
            $args['handler'] = $guzzleHander;
        }
        return new static($args, $appId, $appPassword);
    }

    /**
     * Create a IdentityClient
     *
     * @return IdentityClient
     */
    public function createIdentityClient(): IdentityClient
    {
        return $this->createClient(IdentityClient::class);
    }

    /**
     * Create a LicenseClient
     *
     * @return LicenseClient
     */
    public function createLicenseClient(): LicenseClient
    {
        return $this->createClient(LicenseClient::class);
    }

    /**
     * Create a ProfileClient
     *
     * @return ProfileClient
     */
    public function createProfileClient(): ProfileClient
    {
        return $this->createClient(ProfileClient::class);
    }

    /**
     * Create a EcomClient
     *
     * @return EcomClient
     */
    public function createEcomClient(): EcomClient
    {
        return $this->createClient(EcomClient::class);
    }

    /**
     * Create a Da Client
     *
     * @return DaClient
     */
    public function createDaClient(): DaClient
    {
        return $this->createClient(DaClient::class);
    }

    /**
     * Create a NotificationsClient
     *
     * @return NotificationsClient
     */
    public function createNotificationsClient(): NotificationsClient
    {
        return $this->createClient(NotificationsClient::class);
    }

    /**
     * Create a RewardsClient
     *
     * @return RewardsClient
     */
    public function createRewardsClient(): RewardsClient
    {
        return $this->createClient(RewardsClient::class);
    }

    /**
     * Creates a Client
     *
     * @param string $className
     * @return mixed
     */
    private function createClient(string $className)
    {
        return new $className($this->config, $this->appId, $this->appPassword);
    }

    /**
     * Return the current configuration options
     *
     * @return array<String, mixed>
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Returns the client application ID
     *
     * @return string
     */
    public function getAppId(): string
    {
        return $this->appId;
    }

    /**
     * Sets the client application ID
     *
     * @param string $id Application ID
     * @return self
     */
    public function setAppId(string $id): self
    {
        $this->appId = $id;
        return $this;
    }

    /**
     * Returns the client application password
     *
     * @return string
     */
    public function getAppPassword(): string
    {
        return $this->appPassword;
    }

    /**
     * Sets the client application password
     *
     * @param string $pwd Password
     * @return self
     */
    public function setAppPassword(string $pwd): self
    {
        $this->appPassword = $pwd;
        return $this;
    }

    private function setBaseUriConfig(
        string $idServiceBaseUri,
        string $licenseServiceBaseUri,
        string $profileServiceBaseUri,
        string $ecomServiceBaseUri,
        string $daServiceBaseUri,
        string $notificationsServiceBaseUri,
        string $rewardsServiceBaseUri
    ): void {
        $this->config[self::BASE_URI] = [
            self::BASE_URI_ID        => $idServiceBaseUri,
            self::BASE_URI_LICENSE   => $licenseServiceBaseUri,
            self::BASE_URI_PROFILE   => $profileServiceBaseUri,
            self::BASE_URI_ECOM      => $ecomServiceBaseUri,
            self::BASE_URI_DA        => $daServiceBaseUri,
            self::BASE_URI_NOTIFICATIONS => $notificationsServiceBaseUri,
            self::BASE_URI_REWARDS => $rewardsServiceBaseUri
        ];
    }
}
