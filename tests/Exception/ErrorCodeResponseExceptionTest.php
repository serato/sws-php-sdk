<?php
namespace Serato\SwsSdk\Test\Exception;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Exception\ErrorCodeResponseException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\ClientException;
use Exception;

class ErrorCodeResponseExceptionTest extends AbstractTestCase
{
    /** @var mixed */
    private $mockException;

    public function testConstructor(): void
    {
        $code = 1004;
        $message = 'A meaningful error message';

        $request = new Request(
            'GET',
            'http://my.server.com/my/uri',
            []
        );

        $jsonBody = json_encode(['code' => $code, 'error' => $message]);

        if ($jsonBody === false) {
            throw new Exception('Cannot JSON-encode response body');
        }

        $response = new Response(
            400,
            ['Content-Type' => 'application/json'],
            $jsonBody
        );

        $this->createMockException(new ClientException('Exception message', $request, $response));

        $this->assertRegExp("/$message/", $this->mockException->getMessage());
        $this->assertEquals($this->mockException->getCode(), $code);
    }

    /**
     * @param Exception $e
     */
    private function createMockException($e): void
    {
        $this->mockException = $this->getMockForAbstractClass(ErrorCodeResponseException::class, [$e]);
    }
}
