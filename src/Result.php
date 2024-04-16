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

    public function __construct(private readonly ResponseInterface $response)
    {
        $this->data = $this->parseResponseBody($this->response) ?? [];
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
    #[\Override]
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->data);
    }

    /**
     * Implementation for `ArrayAccess` interface
     *
     * @return mixed
     */
    #[\Override]
    public function offsetGet(mixed $offset)
    {
        if (isset($this->data[$offset])) {
            return $this->data[$offset];
        }
    }

    /**
     * Implementation for `ArrayAccess` interface
     *
     * @return void
     */
    #[\Override]
    public function offsetSet(mixed $offset, mixed $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * Implementation for `ArrayAccess` interface
     *
     * @return boolean
     */
    #[\Override]
    public function offsetExists(mixed $offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * Implementation for `ArrayAccess` interface
     *
     * @return void
     */
    #[\Override]
    public function offsetUnset(mixed $offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * Implementation for `Countable` interface
     */
    #[\Override]
    public function count()
    {
        return count($this->data);
    }
}
