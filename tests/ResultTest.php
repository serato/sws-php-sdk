<?php
namespace Serato\SwsSdk\Test;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Result;
use GuzzleHttp\Psr7\Response;

class ResultTest extends AbstractTestCase
{
    /**
     * @dataProvider validJsonResultConstructorProvider
     */
    public function testValidJsonResultConstructor($httpStatusCode, $body)
    {
        $response = new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($body)
        );

        $result = new Result($response);

        $this->assertEquals($response, $result->getResponse());
        $this->assertEquals(count($body), count($result));
        $this->assertFalse(isset($result['no_such_key']));

        foreach ($body as $k => $v) {
            $this->assertEquals($v, $result[$k]);
        }
    }

    public function validJsonResultConstructorProvider()
    {
        $data = ['var1' => 'val1', 'var2' => 2, 'var3' => 'val3'];
        return [
            [200, $data],
            [400, $data],
            [403, $data]
        ];
    }

    public function testInvalidJsonResultConstructor()
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
     * @dataProvider get204ResponseProvider
     */
    public function test204Responses($response)
    {
        $result = new Result($response);

        $this->assertEquals($response, $result->getResponse());
        $this->assertEquals(0, count($result));
    }

    public function get204ResponseProvider()
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
    public function testInvalidContentType()
    {
        $response = new Response(
            200,
            ['Content-Type' => 'text/html'],
            'not a valid JSON string'
        );

        $result = new Result($response);
    }
}
