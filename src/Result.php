<?php
declare(strict_types=1);

namespace Serato\SwsSdk;

use Psr\Http\Message\ResponseInterface;
use Exception;
use ArrayIterator;
use IteratorAggregate;
use ArrayAccess;
use Countable;

/**
 * SWS Result
 *
 * A simple wrapper around a `Psr\Http\Message\ResponseInterface` that parses
 * the response body and exposes it's data via native PHP array accessor syntax.
 *
 * @implements IteratorAggregate<String, mixed>
 * @implements ArrayAccess<String, mixed>
 */
class Result implements IteratorAggregate, ArrayAccess, Countable
{
    /** @var array<String, mixed> */
    private $data = [];

    /** @var ResponseInterface */
    private $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        $this->data = $this->parseResponseBody($response) ?? [];
    }

    /**
     * Get the associated response
     *
     * @return ResponseInterface|null
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    /**
     * @param ResponseInterface $response
     * @return null|array<String, mixed>
     */
    private function parseResponseBody(ResponseInterface $response): ?array
    {
        if ($response->getStatusCode() !== 204) {
            switch ($response->getHeaderLine('Content-Type')) {
                case 'application/json':
                    return $this->parseJsonResponseBody($response);
                default:
                    // Should never happen :-)
                    $msg = 'Unexpected `Content-Type` `' .
                            $response->getHeaderLine('Content-Type') .
                            '` encountered in `\Serato\SwsSdk\Result::parseResponseBody` with ' .
                            'HTTP response code `' . $response->getStatusCode() . '` and ' .
                            'HTTP response body `' . (string)$response->getBody() . '`';
                    throw new Exception($msg);
            }
        }
        return null;
    }

    /**
     * @param ResponseInterface $response
     * @return null|array<String, mixed>
     */
    private function parseJsonResponseBody(ResponseInterface $response): ?array
    {
        return json_decode((string)$response->getBody(), true);
    }

    /**
     * Implementation for `IteratorAggregate` interface
     *
     * @return ArrayIterator<String, mixed>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->data);
    }

    /**
     * Implementation for `ArrayAccess` interface
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if (isset($this->data[$offset])) {
            return $this->data[$offset];
        }
    }

    /**
     * Implementation for `ArrayAccess` interface
     *
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * Implementation for `ArrayAccess` interface
     *
     * @param mixed $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * Implementation for `ArrayAccess` interface
     *
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * Implementation for `Countable` interface
     */
    public function count()
    {
        return count($this->data);
    }
}
