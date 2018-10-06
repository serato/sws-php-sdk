<?php
namespace Serato\SwsSdk\Test;

use Serato\SwsSdk\Test\AbstractTestCase;
use Serato\SwsSdk\Command;
use InvalidArgumentException;
use DateTime;

class CommandTest extends AbstractTestCase
{
    /**
     * @dataProvider commandConstructRequestProvider
     */
    public function testCommandConstructRequest($httpMethod, $httpScheme, $httpHost, $uriPath)
    {
        $commandMock = $this->getMockForAbstractClass(
            Command::class,
            [
                'my_app',
                'my_pass',
                $httpScheme . '://' . $httpHost,
                []
            ]
        );

        $commandMock->expects($this->any())
            ->method('getHttpMethod')
            ->willReturn($httpMethod);
        $commandMock->expects($this->any())
            ->method('getUriPath')
            ->willReturn($uriPath);
        $commandMock->expects($this->any())
            ->method('getArgsDefinition')
            ->willReturn([]);

        $request = $commandMock->getRequest();

        $this->assertEquals($httpMethod, $request->getMethod());
        $this->assertEquals($httpScheme, $request->getUri()->getScheme());
        $this->assertEquals($httpHost, $request->getUri()->getHost());
        $this->assertEquals($uriPath, $request->getUri()->getPath());
        $this->assertEquals('', $request->getUri()->getQuery());
        $this->assertEquals('', (string)$request->getBody());
    }

    public function commandConstructRequestProvider()
    {
        return [
            ['GET', 'http', 'my.getserver.com', '/my/get/path'],
            ['POST', 'https', 'my.postserver.com', '/my/post/path'],
            ['PUT', 'http', 'my.putserver.com', '/my/put/path']
        ];
    }

    /**
     * @dataProvider commandArgsValidationProvider
     */
    public function testCommandArgsValidation(
        $commandArgDef,
        $commandArgs,
        array $exceptionTexts,
        $assertText
    ) {
        $commandMock = $this->getMockForAbstractClass(
            Command::class,
            [
                'my_app',
                'my_pass',
                $httpScheme . '://' . $httpHost,
                $commandArgs
            ]
        );

        $errorMessage = '';

        try {
            $commandMock->expects($this->any())
                ->method('getArgsDefinition')->willReturn($commandArgDef);
            $commandMock->getRequest();
        } catch (InvalidArgumentException $e) {
            $errorMessage = $e->getMessage();
        }

        if ($exceptionText === '') {
            $this->assertEquals(0, count($exceptionText), $assertText);
        } else {
            foreach ($exceptionTexts as $exceptionText) {
                $this->assertRegExp('/' . $exceptionText . '/', $errorMessage, $assertText);
            }
        }
    }

    public function commandArgsValidationProvider()
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
                ['stringValRequired' => 'string value', 'stringValNotRequired' => new DateTime],
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
                    'dateTimeValRequired' => new DateTime,
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
                    'dateTimeValRequired' => new DateTime,
                    'dateTimeValNotRequired' => new DateTime,
                    'invalidArgument' => 'some value'
                ],
                ['invalid key', 'invalidArgument'],
                'Invalid argument `invalidArgument`'
            ]
        ];
    }
}
