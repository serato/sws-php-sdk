<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test;

use Serato\SwsSdk\Command;
use Serato\SwsSdk\CommandBasicAuth;

class CommandBasicAuthTest extends AbstractTestCase
{
    /** @var mixed */
    private $commandMock;

    public function testCommandHeaders(): void
    {
        $this->createCommandBasicAuthMock();

        $this->commandMock->expects($this->any())
            ->method('getHttpMethod')
            ->willReturn('GET');
        $this->commandMock->expects($this->any())
            ->method('getUriPath')
            ->willReturn('/some/path');
        $this->commandMock->expects($this->any())
            ->method('getArgsDefinition')
            ->willReturn([]);

        $request = $this->commandMock->getRequest();

        $this->assertRegExp('/Basic/', $request->getHeaderLine('Authorization'));
    }

    public function testCdnAuthHeader(): void
    {
        $this->createCommandBasicAuthMock();

        $this->commandMock->expects($this->any())
            ->method('getHttpMethod')
            ->willReturn('GET');
        $this->commandMock->expects($this->any())
            ->method('getUriPath')
            ->willReturn('/some/path');
        $this->commandMock->expects($this->any())
            ->method('getArgsDefinition')
            ->willReturn([]);

        $request = $this->commandMock->getRequest();

        $cdnAuthHeader = $request->getHeaderLine(Command::CUSTOM_CDN_AUTH_HEADER);
        $decodedHeader = base64_decode($cdnAuthHeader);
        $this->assertNotFalse($decodedHeader, 'x-serato-cdn-auth header should be a base-64-encoded string');
        $this->assertEquals('my_cdn_client_id:my_cdn_secret', $decodedHeader);
    }

    /**
     * @return void
     */
    private function createCommandBasicAuthMock(): void
    {
        $this->commandMock = $this->getMockForAbstractClass(
            CommandBasicAuth::class,
            [
                'my_app',
                'my_pass',
                'http://my.server.com',
                [],
                'my_cdn_client_id',
                'my_cdn_secret'
            ]
        );
    }
}
