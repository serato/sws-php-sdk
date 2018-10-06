<?php
namespace Serato\SwsSdk\Test;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\CommandBasicAuth;

class CommandBasicAuthTest extends AbstractTestCase
{
    public function testCommandHeaders()
    {
        $commandMock = $this->getMockForAbstractClass(
            CommandBasicAuth::class,
            [
                'my_app',
                'my_pass',
                'http://my.server.com',
                []
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

        $request = $commandMock->getRequest();

        $this->assertRegExp('/Basic/', $request->getHeaderLine('Authorization'));
    }
}
