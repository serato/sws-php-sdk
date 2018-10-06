<?php
namespace Serato\SwsSdk\Test\Exception;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Exception\ErrorMessageResponseException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\ClientException;

class ErrorMessageResponseExceptionTest extends AbstractTestCase
{
    public function testConstructor()
    {
        $httpResponseCode = 500;
        $message = 'A meaningful error message';

        $request = new Request(
            'GET',
            'http://my.server.com/my/uri',
            []
        );

        $response = new Response(
            $httpResponseCode,
            ['Content-Type' => 'application/json'],
            json_encode(['message' => $message])
        );

        $e = new ClientException('Exception message', $request, $response);

        $clientException = $this->getMockForAbstractClass(ErrorMessageResponseException::class, [$e]);

        $this->assertRegExp("/$message/", $clientException->getMessage());
        $this->assertEquals($clientException->getCode(), $httpResponseCode);
    }
}
