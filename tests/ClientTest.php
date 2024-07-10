<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test;

use Serato\SwsSdk\Command;
use Serato\SwsSdk\Exception\AccessDeniedException;
use Serato\SwsSdk\Exception\BadRequestException;
use Serato\SwsSdk\Exception\ResourceNotFoundException;
use Serato\SwsSdk\Exception\ServerApplicationErrorException;
use Serato\SwsSdk\Exception\ServiceUnavailableException;
use Serato\SwsSdk\Client;
use Serato\SwsSdk\Sdk;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ConnectException;

class ClientTest extends AbstractTestCase
{
    private const BASIC_AUTH_COMMAND_NAME  = 'GetProduct';
    private const BASIC_AUTH_COMMAND_CLASS = '\\Serato\\SwsSdk\\License\\Command\\ProductGet';
    private const BEARER_TOKEN_AUTH_COMMAND_NAME  = 'GetUser';
    private const BEARER_TOKEN_AUTH_COMMAND_CLASS = '\\Serato\\SwsSdk\\Identity\\Command\\UserGet';

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

    public function testGetInvalidCommand(): void
    {
        $this->expectException(\InvalidArgumentException::class);
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

    public function testExecuteInvalidCommand(): void
    {
        $this->expectException(\InvalidArgumentException::class);
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

    public function testExecuteAsyncInvalidCommand(): void
    {
        $this->expectException(\InvalidArgumentException::class);
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

    public function testExecuteMagicMethodInvalidCommand(): void
    {
        $this->expectException(\InvalidArgumentException::class);
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

    public function testExecuteAsyncMagicMethodInvalidCommand(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $clientMock = $this->getMockClient([$this->getMock200Response()]);
        $clientMock->noSuchMethodAsync($this->getCommandArgs());
    }

    public function test400ClientError(): void
    {
        $this->expectException(BadRequestException::class);
        $response = new Response(
            400,
            ['Content-Type' => 'application/json'],
            '{"code":1004,"error":"Invalid `product_type_id` 666"}'
        );
        $clientMock = $this->getMockClient([$response]);
        $clientMock->getProduct($this->getCommandArgs());
    }

    public function test403Response(): void
    {
        $this->expectException(AccessDeniedException::class);
        $response = new Response(
            403,
            ['Content-Type' => 'application/json'],
            '{"code":2000,"error":"Access denied. Invalid grants."}'
        );

        $clientMock = $this->getMockClient([$response]);
        $clientMock->getProduct($this->getCommandArgs());
    }

    public function test404Response(): void
    {
        $this->expectException(ResourceNotFoundException::class);
        $response = new Response(
            404,
            ['Content-Type' => 'application/json'],
            '{"message":"Not found"}'
        );

        $clientMock = $this->getMockClient([$response]);
        $clientMock->getProduct($this->getCommandArgs());
    }

    public function test500Response(): void
    {
        $this->expectException(ServerApplicationErrorException::class);
        $response = new Response(
            500,
            ['Content-Type' => 'application/json'],
            '{"message":"Application Error"}'
        );

        $clientMock = $this->getMockClient([$response]);
        $clientMock->getProduct($this->getCommandArgs());
    }

    public function test503Response(): void
    {
        $this->expectException(ServiceUnavailableException::class);
        $response = new Response(
            503,
            ['Content-Type' => 'application/json'],
            '{"message":"Service Unavailable"}'
        );

        $clientMock = $this->getMockClient([$response]);
        $clientMock->getProduct($this->getCommandArgs());
    }

    public function testRequestTimeout(): void
    {
        $this->expectException(ConnectException::class);
        $exception = new ConnectException(
            "Error Communicating with Server",
            new Request('GET', 'test')
        );

        $clientMock = $this->getMockClient([$exception]);
        $clientMock->getProduct($this->getCommandArgs());
    }

    /**
     * Tests that setting the $cdnAuthSecret constructor argument results in an x-serato-cdn-auth header being added to
     * requests.
     *
     * @return void
     */
    public function testClientRequestCdnAuthHeader(): void
    {
        $clientMock = $this->getMockClient([$this->getMock200Response()]);
        $command = $clientMock->getCommand(self::BASIC_AUTH_COMMAND_NAME, $this->getCommandArgs());
        $request = $command->getRequest();

        $cdnAuthHeader = $request->getHeaderLine(Command::CUSTOM_CDN_AUTH_HEADER);
        $decodedHeader = base64_decode($cdnAuthHeader);
        $this->assertNotFalse($decodedHeader, 'x-serato-cdn-auth header should be a base-64-encoded string');
        $this->assertEquals('my_cdn_client_id:my_cdn_secret', $decodedHeader);
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
     * @param array<mixed, mixed> $mockResponses
     * @return mixed
     */
    protected function getMockClient(array $mockResponses)
    {
        $args = [
            Sdk::BASE_URI => [
                Sdk::BASE_URI_ID        => 'https://id.server.com',
                Sdk::BASE_URI_LICENSE   => 'https://license.server.com',
            ],
            'timeout' => 10,
            'handler' => HandlerStack::create(
                new MockHandler($mockResponses)
            )
        ];

        $clientMock = $this->getMockForAbstractClass(
            Client::class,
            [$args, 'my_app', 'my_pass', 'my_cdn_client_id', 'my_cdn_secret']
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
