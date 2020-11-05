<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Test;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Client;
use Serato\SwsSdk\Sdk;
use Serato\SwsSdk\Result;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ConnectException;
use InvalidArgumentException;

class ClientTest extends AbstractTestCase
{
    const BASIC_AUTH_COMMAND_NAME  = 'GetProduct';
    const BASIC_AUTH_COMMAND_CLASS = '\\Serato\\SwsSdk\\License\\Command\\ProductGet';
    const BEARER_TOKEN_AUTH_COMMAND_NAME  = 'GetUser';
    const BEARER_TOKEN_AUTH_COMMAND_CLASS = '\\Serato\\SwsSdk\\Identity\\Command\\UserGet';

    public function testGetValidBasicAuthCommand(): void
    {
        $clientMock = $this->getMockClient([$this->getMock200Response()]);
        $command = $clientMock->getCommand(self::BASIC_AUTH_COMMAND_NAME, $this->getCommandArgs());
        $this->assertTrue(is_a($command, self::BASIC_AUTH_COMMAND_CLASS));
    }

    public function testGetValidBearerTokenAuthCommand(): void
    {
        $clientMock = $this->getMockClient([$this->getMock200Response()]);
        $command = $clientMock->getCommand(self::BEARER_TOKEN_AUTH_COMMAND_NAME);
        $this->assertTrue(is_a($command, self::BEARER_TOKEN_AUTH_COMMAND_CLASS));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetInvalidCommand(): void
    {
        $clientMock = $this->getMockClient([$this->getMock200Response()]);
        $clientMock->getCommand('NoSuchCommand', $this->getCommandArgs());
    }

    public function testExecuteValidBasicAuthCommand(): void
    {
        $clientMock = $this->getMockClient([$this->getMock200Response()]);
        $result = $clientMock->execute(self::BASIC_AUTH_COMMAND_NAME, $this->getCommandArgs());
        $this->assertEquals(
            $result->getResponse()->getStatusCode(),
            $this->getMock200Response()->getStatusCode()
        );
        $this->assertEquals(
            (string)$result->getResponse()->getBody(),
            (string)$this->getMock200Response()->getBody()
        );
    }

    public function testExecuteValidBearerTokenAuthCommand(): void
    {
        $clientMock = $this->getMockClient([$this->getMock200Response()]);
        $result = $clientMock->execute(self::BEARER_TOKEN_AUTH_COMMAND_NAME);
        $this->assertEquals(
            $result->getResponse()->getStatusCode(),
            $this->getMock200Response()->getStatusCode()
        );
        $this->assertEquals(
            (string)$result->getResponse()->getBody(),
            (string)$this->getMock200Response()->getBody()
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExecuteInvalidCommand(): void
    {
        $clientMock = $this->getMockClient([$this->getMock200Response()]);
        $clientMock->execute('NoSuchCommand', $this->getCommandArgs());
    }

    public function testExecuteAsyncValidCommand(): void
    {
        $clientMock = $this->getMockClient([$this->getMock200Response()]);
        $promise = $clientMock->executeAsync(self::BASIC_AUTH_COMMAND_NAME, $this->getCommandArgs());
        $response = $promise->wait();
        $this->assertEquals(
            $response->getStatusCode(),
            $this->getMock200Response()->getStatusCode()
        );
        $this->assertEquals(
            (string)$response->getBody(),
            (string)$this->getMock200Response()->getBody()
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExecuteAsyncInvalidCommand(): void
    {
        $clientMock = $this->getMockClient([$this->getMock200Response()]);
        $clientMock->executeAsync('NoSuchCommand', $this->getCommandArgs());
    }

    public function testExecuteMagicMethodValidBasicAuthCommand(): void
    {
        $clientMock = $this->getMockClient([$this->getMock200Response()]);
        $result = $clientMock->getUser('my_bearer_token');
        $this->assertEquals(
            $result->getResponse()->getStatusCode(),
            $this->getMock200Response()->getStatusCode()
        );
        $this->assertEquals(
            (string)$result->getResponse()->getBody(),
            (string)$this->getMock200Response()->getBody()
        );
    }

    public function testExecuteMagicMethodValidBearerTokenAuthCommand(): void
    {
        $clientMock = $this->getMockClient([$this->getMock200Response()]);
        $result = $clientMock->getUser();
        $this->assertEquals(
            $result->getResponse()->getStatusCode(),
            $this->getMock200Response()->getStatusCode()
        );
        $this->assertEquals(
            (string)$result->getResponse()->getBody(),
            (string)$this->getMock200Response()->getBody()
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExecuteMagicMethodInvalidCommand(): void
    {
        $clientMock = $this->getMockClient([$this->getMock200Response()]);
        $clientMock->noSuchMethod($this->getCommandArgs());
    }

    public function testExecuteAsyncMagicMethodValidCommand(): void
    {
        $clientMock = $this->getMockClient([$this->getMock200Response()]);
        $promise = $clientMock->getProductAsync($this->getCommandArgs());
        $response = $promise->wait();
        $this->assertEquals(
            $response->getStatusCode(),
            $this->getMock200Response()->getStatusCode()
        );
        $this->assertEquals(
            (string)$response->getBody(),
            (string)$this->getMock200Response()->getBody()
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExecuteAsyncMagicMethodInvalidCommand(): void
    {
        $clientMock = $this->getMockClient([$this->getMock200Response()]);
        $clientMock->noSuchMethodAsync($this->getCommandArgs());
    }

    /**
     * @expectedException \Serato\SwsSdk\Exception\BadRequestException
     */
    public function test400ClientError(): void
    {
        $response = new Response(
            400,
            ['Content-Type' => 'application/json'],
            '{"code":1004,"error":"Invalid `product_type_id` 666"}'
        );
        $clientMock = $this->getMockClient([$response]);
        $clientMock->getProduct($this->getCommandArgs());
    }

    /**
     * @expectedException \Serato\SwsSdk\Exception\AccessDeniedException
     */
    public function test403Response(): void
    {
        $response = new Response(
            403,
            ['Content-Type' => 'application/json'],
            '{"code":2000,"error":"Access denied. Invalid grants."}'
        );

        $clientMock = $this->getMockClient([$response]);
        $clientMock->getProduct($this->getCommandArgs());
    }

    /**
     * @expectedException \Serato\SwsSdk\Exception\ResourceNotFoundException
     */
    public function test404Response(): void
    {
        $response = new Response(
            404,
            ['Content-Type' => 'application/json'],
            '{"message":"Not found"}'
        );

        $clientMock = $this->getMockClient([$response]);
        $clientMock->getProduct($this->getCommandArgs());
    }

    /**
     * @expectedException \Serato\SwsSdk\Exception\ServerApplicationErrorException
     */
    public function test500Response(): void
    {
        $response = new Response(
            500,
            ['Content-Type' => 'application/json'],
            '{"message":"Application Error"}'
        );

        $clientMock = $this->getMockClient([$response]);
        $clientMock->getProduct($this->getCommandArgs());
    }

    /**
     * @expectedException \Serato\SwsSdk\Exception\ServiceUnavailableException
     */
    public function test503Response(): void
    {
        $response = new Response(
            503,
            ['Content-Type' => 'application/json'],
            '{"message":"Service Unavailable"}'
        );

        $clientMock = $this->getMockClient([$response]);
        $clientMock->getProduct($this->getCommandArgs());
    }

    /**
     * @expectedException GuzzleHttp\Exception\ConnectException
     */
    public function testRequestTimeout(): void
    {
        $exception = new ConnectException(
            "Error Communicating with Server",
            new Request('GET', 'test')
        );

        $clientMock = $this->getMockClient([$exception]);
        $clientMock->getProduct($this->getCommandArgs());
    }

    protected function getMock200Response(): Response
    {
        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            '{"var1":"val1"}'
        );
    }

    /**
     * @return array{'product_id': String}
     */
    protected function getCommandArgs(): array
    {
        return ['product_id' => '100-100'];
    }

    /**
     * Undocumented function
     *
     * @param array<mixed> $mockResponses
     * @return mixed
     */
    protected function getMockClient(array $mockResponses)
    {
        $args = [
            Sdk::BASE_URI => [
                Sdk::BASE_URI_ID        => 'https://id.server.com',
                Sdk::BASE_URI_LICENSE   => 'https://license.server.com',
            ],
            'handler' => HandlerStack::create(
                new MockHandler($mockResponses)
            )
        ];
        
        $clientMock = $this->getMockForAbstractClass(
            Client::class,
            [$args, 'my_app', 'my_pass']
        );

        $clientMock->expects($this->any())
            ->method('getBaseUri')
            ->willReturn('https://license.server.com');

        $clientMock->expects($this->any())
            ->method('getCommandMap')
            ->willReturn([
                self::BASIC_AUTH_COMMAND_NAME => self::BASIC_AUTH_COMMAND_CLASS,
                self::BEARER_TOKEN_AUTH_COMMAND_NAME  => self::BEARER_TOKEN_AUTH_COMMAND_CLASS
            ]);

        return $clientMock;
    }
}
