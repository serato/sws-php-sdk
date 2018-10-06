<?php
namespace Serato\SwsSdk\Test\Exception;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Exception\ErrorCodeResponseException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\ClientException;

class ErrorCodeResponseExceptionTest extends AbstractTestCase
{
    public function testConstructor()
    {
        $code = 1004;
        $message = 'A meaningful error message';

        $request = new Request(
            'GET',
            'http://my.server.com/my/uri',
            []
        );

        $response = new Response(
            400,
            ['Content-Type' => 'application/json'],
            json_encode(['code' => $code, 'error' => $message])
        );

        $e = new ClientException('Exception message', $request, $response);

        $clientException = $this->getMockForAbstractClass(ErrorCodeResponseException::class, [$e]);

        $this->assertRegExp("/$message/", $clientException->getMessage());
        $this->assertEquals($clientException->getCode(), $code);
    }
}
