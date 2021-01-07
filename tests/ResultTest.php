<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Test;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Result;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;
use Exception;

class ResultTest extends AbstractTestCase
{
    /**
     * @param string $httpStatusCode
     * @param array<mixed> $body
     * @return void
     *
     * @dataProvider validJsonResultConstructorProvider
     */
    public function testValidJsonResultConstructor($httpStatusCode, $body): void
    {
        $jsonBody = json_encode($body);
        if ($jsonBody === false) {
            throw new Exception('Invalid `body` argument. Could not be JSON-encoded.');
        }
        $response = new Response(
            200,
            ['Content-Type' => 'application/json'],
            $jsonBody
        );

        $result = new Result($response);

        $this->assertEquals($response, $result->getResponse());
        $this->assertEquals(count($body), count($result));
        $this->assertFalse(isset($result['no_such_key']));

        foreach ($body as $k => $v) {
            $this->assertEquals($v, $result[$k]);
        }
    }

    /**
     * @return array<array<mixed>>
     */
    public function validJsonResultConstructorProvider(): array
    {
        $data = ['var1' => 'val1', 'var2' => 2, 'var3' => 'val3'];
        return [
            [200, $data],
            [400, $data],
            [403, $data]
        ];
    }

    public function testInvalidJsonResultConstructor(): void
    {
        $response = new Response(
            200,
            ['Content-Type' => 'application/json'],
            'not a valid JSON string'
        );

        $result = new Result($response);

        $this->assertEquals($response, $result->getResponse());
        $this->assertEquals(0, count($result));
    }

    /**
     * 204 responses are not required to send a `Content-Type': application/json` header
     *
     * @param ResponseInterface $response
     * @return void
     *
     * @dataProvider get204ResponseProvider
     */
    public function test204Responses(ResponseInterface $response): void
    {
        $result = new Result($response);

        $this->assertEquals($response, $result->getResponse());
        $this->assertEquals(0, count($result));
    }

    /**
     * @return array<array<ResponseInterface>>
     */
    public function get204ResponseProvider(): array
    {
        return [
            [new Response(204, [])],
            [new Response(204, [], null)],
            [new Response(204, [], '')],
            [new Response(204, [], 'Some content which should not be here')],
            [new Response(204, ['Content-Type' => 'application/json'])],
            [new Response(204, ['Content-Type' => 'application/json'], null)],
            [new Response(204, ['Content-Type' => 'application/json'], '')],
            [
                new Response(
                    204,
                    ['Content-Type' => 'application/json'],
                    'Some content which should not be here'
                )
            ]
        ];
    }


    /**
     * @expectedException \Exception
     */
    public function testInvalidContentType(): void
    {
        $response = new Response(
            200,
            ['Content-Type' => 'text/html'],
            'not a valid JSON string'
        );

        $result = new Result($response);
    }
}
