<?php
namespace Serato\SwsSdk\Test;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\CommandBearerTokenAuth;

class CommandBearerTokenAuthTest extends AbstractTestCase
{
    public function testCommandHeaders()
    {
        $commandMock = $this->getMockForAbstractClass(
            CommandBearerTokenAuth::class,
            [
                'my_app',
                'my_pass',
                'http://my.server.com'
            ]
        );

        $commandMock->expects($this->any())
            ->method('getHttpMethod')
            ->willReturn('GET');
        $commandMock->expects($this->any())
            ->method('getUriPath')
            ->willReturn('/some/path');
        $commandMock->expects($this->any())
            ->method('getArgsDefinition')
            ->willReturn([]);

        $request = $commandMock->getRequest('bearer_token_value');

        $this->assertRegExp('/Bearer/', $request->getHeaderLine('Authorization'));
        $this->assertRegExp('/bearer_token_value/', $request->getHeaderLine('Authorization'));
    }
}
