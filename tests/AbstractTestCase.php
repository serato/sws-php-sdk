<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test;

use PHPUnit\Framework\TestCase;
use Serato\SwsSdk\Sdk;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Serato\SwsSdk\Result;
use Exception;

abstract class AbstractTestCase extends TestCase
{
    /**
     * Constructs a test case with the given name.
     *
     * @param string $name
     * @param array<mixed>  $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        // Error reporting as defined in php.ini file
        error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
    }

    /**
     * Creates a Serato\SwsSdk\Sdk instance with a mock Response handler
     * that contains a single HTTP 200 Response object
     *
     * @param string $responseBody String representation of response body
     * @return Sdk
     */
    public function getSdkWithMocked200Response(string $responseBody): Sdk
    {
        return new Sdk(
            [
                Sdk::BASE_URI => [
                    Sdk::BASE_URI_ID        => 'http://id.server.com',
                    Sdk::BASE_URI_LICENSE   => 'http://license.server.com',
                    Sdk::BASE_URI_PROFILE   => 'https://profile.server.com',
                    Sdk::BASE_URI_ECOM      => 'http://ecom.server.com',
                    Sdk::BASE_URI_DA      => 'http://da.server.com',
                    Sdk::BASE_URI_NOTIFICATIONS      => 'http://notifications.server.com',
                ],
                'handler' => HandlerStack::create(
                    new MockHandler([
                        new Response(
                            200,
                            ['Content-Type' => 'application/json'],
                            $responseBody
                        )
                    ])
                )
            ],
            'app_id',
            'app_password'
        );
    }

    /**
     * Returns a PSR-7 Response object from a `Serato\SwsSdk\Result` object
     * and throws an exception of the response object is not present in the result
     *
     * @param Result $result
     * @return ResponseInterface
     * @throws Exception
     */
    public function getResponseObjectFromResult(Result $result): ResponseInterface
    {
        $response = $result->getResponse();
        if ($response === null) {
            throw new Exception();
        }
        return $response;
    }
}
