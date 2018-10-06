<?php

namespace Serato\SwsSdk;

use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Psr7\Request;
use InvalidArgumentException;
use DateTime;

/**
 * An encapsulation of the parameters and handler that will be used in an HTTP
 * request to an SWS endpoint.
 */
abstract class Command
{
    const ARG_TYPE_STRING   = 'string';
    const ARG_TYPE_INTEGER  = 'integer';
    const ARG_TYPE_DATETIME = 'datetime';

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
     * Base URI of the Command
     *
     * @var string
     */
    protected $baseUri;

    /**
     * Command arguments (specified as name/value pairs)
     *
     * @var array
     */
    protected $commandArgs = [];

    /**
     * Request headers for the command
     *
     * @var array
     */
    protected $requestHeaders = ['Accept' => 'application/json'];

    /**
     * Constructs the Command
     *
     * @param string    $appId          Client application ID
     * @param string    $appPassword    Client application password
     * @param string    $baseUri        Base request URI
     * @param array     $args           Command arguments
     */
    public function __construct(
        string $appId,
        string $appPassword,
        string $baseUri,
        array $args = []
    ) {
        $this->appId        = $appId;
        $this->appPassword  = $appPassword;
        $this->baseUri      = rtrim($baseUri, '/');
        $this->commandArgs  = $args;
    }

    /**
     * Get the PSR-7 Request object for the Command
     *
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        $this->validateCommandArgs();
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
                        if (!is_a($value, '\DateTime')) {
                            throw new InvalidArgumentException("Command arg `$name` must be of type DateTime");
                        }
                        break;
                }
            }
        }
        foreach ($this->getArgsDefinition() as $name => $def) {
            if (isset($def['required'])
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
     * @param array     $args   Array to encode
     *
     * @return string
     */
    protected function arrayToFormUrlEncodedBody(array $args): string
    {
        $def = $this->getArgsDefinition();
        $stringArgs = [];
        foreach ($args as $name => $value) {
            switch ($def[$name]['type']) {
                case self::ARG_TYPE_STRING:
                    $stringArgs[$name] = $value;
                    break;
                case self::ARG_TYPE_INTEGER:
                    $stringArgs[$name] = (string)$value;
                    break;
                case self::ARG_TYPE_DATETIME:
                    $stringArgs[$name] = $value->format(DateTime::ATOM);
                    break;
            }
        }
        return http_build_query($stringArgs);
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
     * @return array
     */
    abstract protected function getArgsDefinition(): array;
}
