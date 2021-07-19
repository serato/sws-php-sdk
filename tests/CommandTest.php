<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Test;

use Serato\SwsSdk\FirewallHeader;
use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Command;
use InvalidArgumentException;
use DateTime;

class CommandTest extends AbstractTestCase
{
    /** @var mixed */
    private $commandMock;

    /**
     * @dataProvider commandConstructRequestProvider
     */
    public function testCommandConstructRequest(
        string $httpMethod,
        string $httpScheme,
        string $httpHost,
        string $uriPath
    ): void {
        $this->createCommandMock($httpScheme, $httpHost);

        $this->commandMock->expects($this->any())
            ->method('getHttpMethod')
            ->willReturn($httpMethod);
        $this->commandMock->expects($this->any())
            ->method('getUriPath')
            ->willReturn($uriPath);
        $this->commandMock->expects($this->any())
            ->method('getArgsDefinition')
            ->willReturn([]);

        $request = $this->commandMock->getRequest();

        $this->assertEquals($httpMethod, $request->getMethod());
        $this->assertEquals($httpScheme, $request->getUri()->getScheme());
        $this->assertEquals($httpHost, $request->getUri()->getHost());
        $this->assertEquals($uriPath, $request->getUri()->getPath());
        $this->assertEquals('', $request->getUri()->getQuery());
        $this->assertEquals('', (string)$request->getBody());
    }

    /**
     * @return array<array<String>>
     */
    public function commandConstructRequestProvider(): array
    {
        return [
            ['GET', 'http', 'my.getserver.com', '/my/get/path'],
            ['POST', 'https', 'my.postserver.com', '/my/post/path'],
            ['PUT', 'http', 'my.putserver.com', '/my/put/path']
        ];
    }

    /**
     * Undocumented function
     *
     * @param array<mixed> $commandArgDef
     * @param array<mixed> $commandArgs
     * @param array<String> $exceptionTexts
     * @param string $assertText
     * @return void
     *
     * @dataProvider commandArgsValidationProvider
     */
    public function testCommandArgsValidation(
        array $commandArgDef,
        array $commandArgs,
        array $exceptionTexts,
        string $assertText
    ): void {
        $this->createCommandMock('http', 'myhost', $commandArgs);

        $errorMessage = '';

        try {
            $this->commandMock->expects($this->any())
                ->method('getArgsDefinition')->willReturn($commandArgDef);
            $this->commandMock->getRequest();
        } catch (InvalidArgumentException $e) {
            $errorMessage = $e->getMessage();
        }

        foreach ($exceptionTexts as $exceptionText) {
            $this->assertRegExp('/' . $exceptionText . '/', $errorMessage, $assertText);
        }
    }

    /**
     * Asserts that the X-Serato-Firewall header is included in requests, and that it matches the expected pattern
     *
     * @dataProvider commandConstructRequestProvider
     */
    public function testCommandFirewallHeader(
        string $httpMethod,
        string $httpScheme,
        string $httpHost,
        string $uriPath
    ): void {
        $this->createCommandMock($httpScheme, $httpHost);

        $this->commandMock->expects($this->any())
            ->method('getHttpMethod')
            ->willReturn($httpMethod);
        $this->commandMock->expects($this->any())
            ->method('getUriPath')
            ->willReturn($uriPath);
        $this->commandMock->expects($this->any())
            ->method('getArgsDefinition')
            ->willReturn([]);

        $request = $this->commandMock->getRequest();

        $firewallHeader = $request->getHeaderLine(Command::CUSTOM_FIREWALL_HEADER);
        $this->assertRegExp(FirewallHeader::HEADER_PATTERN, $firewallHeader);
    }

    /**
     * @return array<array<mixed>>>
     */
    public function commandArgsValidationProvider(): array
    {
        $commandArgDef = [
            'stringValRequired' => ['type' => Command::ARG_TYPE_STRING, 'required' => true],
            'stringValNotRequired' => ['type' => Command::ARG_TYPE_STRING],
            'intValRequired' => ['type' => Command::ARG_TYPE_INTEGER, 'required' => true],
            'intValNotRequired' => ['type' => Command::ARG_TYPE_INTEGER],
            'dateTimeValRequired' => ['type' => Command::ARG_TYPE_DATETIME, 'required' => true],
            'dateTimeValNotRequired' => ['type' => Command::ARG_TYPE_DATETIME]
        ];

        return [
            [
                $commandArgDef,
                [],
                ['stringValRequired', 'required'],
                'No args provided'
            ],
            [
                $commandArgDef,
                ['stringValRequired' => 111],
                ['stringValRequired', 'type string'],
                'Invalid type for `stringValRequired`'
            ],
            [
                $commandArgDef,
                ['stringValRequired' => 'string value', 'stringValNotRequired' => new DateTime()],
                ['stringValNotRequired', 'type string'],
                'Invalid type for `stringValNotRequired`'
            ],
            [
                $commandArgDef,
                [
                    'stringValRequired' => 'string value',
                    'stringValNotRequired' => 'another string value'
                ],
                ['intValRequired', 'required'],
                'No value provided for `intValRequired`'
            ],
            [
                $commandArgDef,
                [
                    'stringValRequired' => 'string value',
                    'stringValNotRequired' => 'another string value',
                    'intValRequired' => 'string value'
                ],
                ['intValRequired', 'type integer'],
                'Invalid type for `intValRequired`'
            ],
            [
                $commandArgDef,
                [
                    'stringValRequired' => 'string value',
                    'stringValNotRequired' => 'another string value',
                    'intValRequired' => 1111
                ],
                ['dateTimeValRequired', 'required'],
                'No value provided for `dateTimeValRequired`'
            ],
            [
                $commandArgDef,
                [
                    'stringValRequired' => 'string value',
                    'stringValNotRequired' => 'another string value',
                    'intValRequired' => 1111,
                    'dateTimeValRequired' => new DateTime(),
                    'dateTimeValNotRequired' => 23232
                ],
                ['dateTimeValNotRequired', 'type DateTime'],
                'Invalid type for `dateTimeValNotRequired`'
            ],
            [
                $commandArgDef,
                [
                    'stringValRequired' => 'string value',
                    'stringValNotRequired' => 'another string value',
                    'intValRequired' => 1111,
                    'dateTimeValRequired' => new DateTime(),
                    'dateTimeValNotRequired' => new DateTime(),
                    'invalidArgument' => 'some value'
                ],
                ['invalid key', 'invalidArgument'],
                'Invalid argument `invalidArgument`'
            ]
        ];
    }

    /**
     * @param string $httpScheme
     * @param string $httpHost
     * @param array<mixed> $commandArgs
     * @return void
     */
    private function createCommandMock(string $httpScheme, string $httpHost, array $commandArgs = []): void
    {
        $this->commandMock = $this->getMockForAbstractClass(
            Command::class,
            [
                'my_app',
                'my_pass',
                $httpScheme . '://' . $httpHost,
                $commandArgs
            ]
        );
    }
}
