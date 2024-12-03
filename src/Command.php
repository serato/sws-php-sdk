<?php

declare(strict_types=1);

namespace Serato\SwsSdk;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use GuzzleHttp\Psr7\Request;
use InvalidArgumentException;
use DateTime;

/**
 * An encapsulation of the parameters and handler that will be used in an HTTP
 * request to an SWS endpoint.
 */
abstract class Command
{
    public const ARG_TYPE_STRING   = 'string';
    public const ARG_TYPE_INTEGER  = 'integer';
    public const ARG_TYPE_DATETIME = 'datetime';
    public const ARG_TYPE_BOOLEAN = 'boolean';

    /**
     * The custom header that identifies requests from the SDK to the application firewall
     */
    public const CUSTOM_FIREWALL_HEADER = 'X-Serato-Firewall';

    /**
     * Custom header that identifies requests from the SDK to test stack CDNs
     */
    public const CUSTOM_CDN_AUTH_HEADER = 'X-Serato-Cdn-Auth';

    /**
     * Client application ID
     *
     * @var string
     */
    protected $appId;

    /**
     * Client application password
     *
     * @var string
     */
    protected $appPassword;

    /**
     * Client identifier used to identify the application to test stack CDNs
     *
     * @var string
     */
    protected $cdnAuthId;

    /**
     * Secret used to authenticate requests to test stack CDNs. $appId is currently used as the client ID in these
     * credentials.
     *
     * @var string
     */
    protected $cdnAuthSecret;

    /**
     * Base URI of the Command
     *
     * @var string
     */
    protected $baseUri;

    /**
     * Command arguments (specified as name/value pairs)
     *
     * @var array<String, String|Integer|DateTime|Boolean>
     */
    protected $commandArgs = [];

    /**
     * Request headers for the command
     *
     * @var array<String, String>
     */
    protected $requestHeaders = ['Accept' => 'application/json'];

    /**
     * Constructs the Command
     *
     * @param string    $appId          Client application ID
     * @param string    $appPassword    Client application password
     * @param string    $baseUri        Base request URI
     * @param string    $cdnAuthId      Client identifier used to identify the application to test stack CDNs
     * @param string    $cdnAuthSecret  Secret used to authenticate requests to test stack CDNs
     * @param array<String, String|Integer|DateTime|Boolean>     $args           Command arguments
     */
    public function __construct(
        string $appId,
        string $appPassword,
        string $baseUri,
        array $args = [],
        string $cdnAuthId = '',
        string $cdnAuthSecret = ''
    ) {
        $this->appId         = $appId;
        $this->appPassword   = $appPassword;
        $this->baseUri       = rtrim($baseUri, '/');
        $this->commandArgs   = $args;
        $this->cdnAuthId     = $cdnAuthId;
        $this->cdnAuthSecret = $cdnAuthSecret;
    }

    /**
     * Casts values to a string. Supports any of the allowable command paramter types.
     * ie. String, Integer, DateTime, Boolean
     *
     * @param String|Integer|DateTime|Boolean $value
     * @return string
     * @throws InvalidArgumentException
     */
    public static function toString($value): string
    {
        if (is_string($value)) {
            return $value;
        } elseif (is_int($value)) {
            return (string)$value;
        } elseif (is_a($value, DateTime::class)) {
            return $value->format(DateTime::ATOM);
        } elseif (is_bool($value)) {
            return (string)$value;
        }
        throw new InvalidArgumentException(
            'Invalid argment type. Only string, integer, boolean and DateTime types are supported'
        );
    }

    /**
     * Get the PSR-7 Request object for the Command
     *
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        $this->validateCommandArgs();
        $this->setCdnAuthHeader();
        $this->setFirewallRequestHeader();
        $this->setCommandRequestHeaders();
        return new Request(
            $this->getHttpMethod(),
            $this->baseUri .
                $this->getUriPath() .
                ($this->getUriQuery() === '' ? '' : '?' . $this->getUriQuery()),
            $this->requestHeaders,
            $this->getBody(),
            $this->getHttpVersion()
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    private function validateCommandArgs(): void
    {
        $def = $this->getArgsDefinition();
        foreach ($this->commandArgs as $name => $value) {
            if (!isset($def[$name])) {
                throw new InvalidArgumentException("Command `args` contains invalid key '$name'");
            } else {
                switch ($def[$name]['type']) {
                    case self::ARG_TYPE_STRING:
                        if (!is_string($value)) {
                            throw new InvalidArgumentException("Command arg `$name` must be of type string");
                        }
                        break;
                    case self::ARG_TYPE_INTEGER:
                        if (!is_int($value)) {
                            throw new InvalidArgumentException("Command arg `$name` must be of type integer");
                        }
                        break;
                    case self::ARG_TYPE_DATETIME:
                        if (is_int($value) || !is_a($value, '\DateTime')) {
                            throw new InvalidArgumentException("Command arg `$name` must be of type DateTime");
                        }
                        break;
                    case self::ARG_TYPE_BOOLEAN:
                        if (!is_bool($value)) {
                            throw new InvalidArgumentException("Command arg `$name` must be of type boolean");
                        }
                        break;
                }
            }
        }
        foreach ($this->getArgsDefinition() as $name => $def) {
            if (
                isset($def['required'])
                    && $def['required'] === true
                    && !isset($this->commandArgs[$name])
            ) {
                throw new InvalidArgumentException("Command arg `$name` is required");
            }
        }
    }

    /**
     * Convert an array into a string that is suitable for use in the body of
     * HTTP request whose `Content-Type` is `x-www-form-urlencoded`.
     *
     * @param array<String, String|Integer|DateTime>     $args   Array to encode
     *
     * @return string
     */
    protected function arrayToFormUrlEncodedBody(array $args): string
    {
        $def = $this->getArgsDefinition();
        $stringArgs = [];
        foreach ($args as $name => $value) {
            $stringArgs[$name] = self::toString($value);
        }
        return http_build_query($stringArgs);
    }

    /**
     * Sets a custom header that authenticates requests to test stack CloudFront distributions. The SWS client ID is
     * currently reused as the client identifier in the CDN auth header. This header is only set in test environments.
     *
     * @return Command The current command, with the CDN auth header set
     */
    protected function setCdnAuthHeader(): self
    {
        // This secret should only be set in test environments. It is an empty string by default.
        if ($this->cdnAuthSecret !== '') {
            $encodedCredentials = base64_encode("{$this->cdnAuthId}:{$this->cdnAuthSecret}");
            return $this->setRequestHeader(self::CUSTOM_CDN_AUTH_HEADER, $encodedCredentials);
        } else {
            return $this;
        }
    }

    /**
     * Sets a custom request header for the Command that identifies the request to the application firewall.
     *
     * @return Command The current command, with the firewall request header set
     */
    protected function setFirewallRequestHeader(): self
    {
        $firewallHeader = new FirewallHeader();
        return $this->setRequestHeader(self::CUSTOM_FIREWALL_HEADER, $firewallHeader->getHeaderValue());
    }

    /**
     * Set a request header for the Command
     *
     * @param string $name Header name
     * @param string $value Header value
     *
     * @return self
     */
    protected function setRequestHeader(string $name, string $value): self
    {
        $this->requestHeaders[$name] = (string)$value;
        return $this;
    }

    /**
     * Set request headers specific to the Command
     *
     * @return void
     */
    protected function setCommandRequestHeaders(): void
    {
        // Override in child classes
    }

    /**
     * Get the HTTP version
     *
     * @return string
     */
    public function getHttpVersion(): string
    {
        return '1.1';
    }

    /**
     * Get the request body
     *
     * @return string|null|resource|StreamInterface
     */
    public function getBody()
    {
        return null;
    }

    /**
     * Get the query component of the URI.
     *
     * eg. For `http://my.server.com/my/path?my=query` the query is `my=query`.
     *
     * @return string
     */
    public function getUriQuery(): string
    {
        return '';
    }

    /**
     * Get the HTTP method
     *
     * @return string
     */
    abstract public function getHttpMethod(): string;

    /**
     * Get the path component of the URI. ie. Everything after the base URI.
     *
     * eg. In the URI `http://my.server.com/my/path` the path is `/my/path`.
     *
     * @return string
     */
    abstract public function getUriPath(): string;

    /**
     * Defines the requirements of the `$args` array passed to the Commands
     * constructor.
     *
     * The key of an array item is the argument name. The value
     * of an array item is array with the keys `type` (required) and `required`
     * (optional).
     *
     * `type` is a string value and must be one of `string`, `integer` of `datetime`.
     *
     * @return array<String, array{'type': String|Integer|DateTime, 'required': Boolean}>
     */
    abstract protected function getArgsDefinition(): array;
}
