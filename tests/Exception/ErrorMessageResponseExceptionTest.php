<?php
namespace Serato\SwsSdk\Test\Exception;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Exception\ErrorMessageResponseException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\ClientException;
use Exception;

class ErrorMessageResponseExceptionTest extends AbstractTestCase
{
    /** @var mixed */
    private $mockException;

    public function testConstructor(): void
    {
        $httpResponseCode = 500;
        $message = 'A meaningful error message';

        $request = new Request(
            'GET',
            'http://my.server.com/my/uri',
            []
        );

        $jsonBody = json_encode(['message' => $message]);

        if ($jsonBody === false) {
            throw new Exception('Cannot JSON-encode response body');
        }

        $response = new Response(
            $httpResponseCode,
            ['Content-Type' => 'application/json'],
            $jsonBody
        );

        $this->createMockException(new ClientException('Exception message', $request, $response));

        $this->assertRegExp("/$message/", $this->mockException->getMessage());
        $this->assertEquals($this->mockException->getCode(), $httpResponseCode);
    }

    /**
     * @param Exception $e
     * @return void
     */
    private function createMockException($e): void
    {
        $this->mockException = $this->getMockForAbstractClass(ErrorMessageResponseException::class, [$e]);
    }
}
