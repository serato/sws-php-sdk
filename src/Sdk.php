<?php

namespace Serato\SwsSdk;

use Serato\SwsSdk\Identity\IdentityClient;
use Serato\SwsSdk\License\LicenseClient;
use InvalidArgumentException;

/**
 * Builds SWS clients based on configuration settings.
 */
class Sdk
{
    const BASE_URI                       = 'base_uri';
    const BASE_URI_ID                    = 'id';
    const BASE_URI_LICENSE               = 'license';

    const ENV_PRODUCTION                = 'production';
    const ENV_STAGING                   = 'staging';

    const BASE_URI_STAGING_ID           = 'https://staging-id.serato.net';
    const BASE_URI_STAGING_LICENSE      = 'https://staging-license.serato.net';
    const BASE_URI_PRODUCTION_ID        = 'https://id.serato.io';
    const BASE_URI_PRODUCTION_LICENSE   = 'https://license.serato.io';

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
     * @var array
     */
    private $config = [
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
     *   must contains key named `id` and `license` and provide a complete base URI
     *   including protocol (http or https) for each. One of `env` or `base_uri`
     *   must be specified.
     * - `timeout`: (float) The default request timeout, in seconds.
     * - `handler`: (callable) Function that transfers HTTP requests over the
     *   wire. Passed to Guzzle clients to override the default handler
     *   (eg. to use a mock handler). See the Guzzle docs for more info.
     *
     * @link http://guzzle.readthedocs.io/en/latest/quickstart.html Guzzle documentation
     *
     * @param string    $appId          Client application ID
     * @param string    $appPassword    Client application password
     * @param array     $args           Client configuration arguments
     *
     * @throws InvalidArgumentException If any required options are missing or
     *                                   an invalid value is provided.
     */
    public function __construct($appId, $appPassword, array $args)
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
                        self::BASE_URI_STAGING_LICENSE
                    );
                }
                if ($args['env'] == self::ENV_PRODUCTION) {
                    $this->setBaseUriConfig(
                        self::BASE_URI_PRODUCTION_ID,
                        self::BASE_URI_PRODUCTION_LICENSE
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
            if (!is_array($args[self::BASE_URI]) ||
                !isset($args[self::BASE_URI][self::BASE_URI_ID]) ||
                !isset($args[self::BASE_URI][self::BASE_URI_LICENSE])
            ) {
                throw new InvalidArgumentException(
                    'The `base_uri` config option value must be an array containing '.
                    '`id` and `license` keys.',
                    1002
                );
            } else {
                if (strpos($args[self::BASE_URI][self::BASE_URI_ID], 'http://') !== 0 &&
                    strpos($args[self::BASE_URI][self::BASE_URI_ID], 'https://') !== 0
                ) {
                    throw new InvalidArgumentException(
                        'The `base_uri` `id` config option value must including a ' .
                        'valid network protocol (ie. http or https)',
                        1003
                    );
                }
                if (strpos($args[self::BASE_URI][self::BASE_URI_LICENSE], 'http://') !== 0 &&
                    strpos($args[self::BASE_URI][self::BASE_URI_LICENSE], 'https://') !== 0
                ) {
                    throw new InvalidArgumentException(
                        'The `base_uri` `license` config option value must including a ' .
                        'valid network protocol (ie. http or https)',
                        1004
                    );
                }
                $this->setBaseUriConfig(
                    $args[self::BASE_URI][self::BASE_URI_ID],
                    $args[self::BASE_URI][self::BASE_URI_LICENSE]
                );
            }
        }

        if (!isset($this->config[self::BASE_URI])) {
            throw new InvalidArgumentException(
                'You must specify one of either the `env` or `base_uri` config ' .
                'option values.',
                1005
            );
        }
    }

    /**
     * Create a IdentityClient
     *
     * @return IdentityClient
     */
    public function createIdentityClient()
    {
        return $this->createClient('Serato\\SwsSdk\\Identity\\IdentityClient');
    }

    /**
     * Create a LicenseClient
     *
     * @return LicenseClient
     */
    public function createLicenseClient()
    {
        return $this->createClient('Serato\\SwsSdk\\License\\LicenseClient');
    }

    private function createClient($className)
    {
        return new $className($this->appId, $this->appPassword, $this->config);
    }

    /**
     * Return the current configuration options
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    private function setBaseUriConfig($idServiceBaseUri, $licenseServiceBaseUri)
    {
        $this->config[self::BASE_URI] = [
            self::BASE_URI_ID        => $idServiceBaseUri,
            self::BASE_URI_LICENSE   => $licenseServiceBaseUri
        ];
    }
}
