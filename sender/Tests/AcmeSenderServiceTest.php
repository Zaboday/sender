<?php

use PHPUnit\Framework\TestCase;
use Sender\Converters\XmlConverter;

class AcmeSenderServiceTest extends TestCase
{
    /**
     * @var \Sender\Request\AcmeRequestOptions
     */
    private $requestOptions;
    private $startTag = 'payload';
    private $from = ['foo' => 1, 'bar' => 2, 'barBar' => 'Some text'];
    private $validDummyXml;
    private $validBarXmlResponse;
    private $successBarXmlResponse;

    /**
     * @var \Sender\AcmeSenderService
     */
    private $service;

    public function setUp()
    {
        $rootTag = \Sender\Request\AcmeRequestOptions::ROOT_DATA_REQUEST_KEY;
        $successCode = \Sender\Response\AcmeResponseValidator::CODE_SUCCESS;
        $successResultValue = \Sender\Response\AcmeResponseValidator::SUCCESS_VALUE;
        $this->validDummyXml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<$rootTag><foo>1</foo><bar>2</bar><barBar>Some text</barBar></$rootTag>\n";
        $this->validBarXmlResponse = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<$rootTag><code>A125</code><description>Some descr</description><result>Some descr</result></$rootTag>\n";
        $this->successBarXmlResponse = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<$rootTag><code>$successCode</code><description>Some descr</description><result>$successResultValue</result><order>number</order><transaction>some</transaction><bar>123</bar></$rootTag>\n";
    }

    public function tearDown()
    {
        $this->service = null;
    }

    /**
     * @expectedException        \Sender\Exceptions\ServerResponseException
     * @expectedExceptionMessage  Can't convert from XML to data. Invalid XML format.
     */
    public function testResponseContentExceptionThrown()
    {
        $serverResponse = new \Sender\HttpRequest\ServerResponseDto(500, 'some invalid xml content');
        $stub = $this->createMock(\Sender\HttpRequest\HttpRequestInterface::class);
        $stub->method('request')
            ->willReturn($serverResponse);

        $this->service = new \Sender\AcmeSenderService(
            '11',
            'pass',
            $stub,
            new \Sender\Factories\AcmeApiXmlFactory()
        );
        $this->service->sendFoo(['job' => '1111', 'transaction' => 'aaa']);
    }

    /**
     * @expectedException        \Sender\Exceptions\ServerResponseException
     * @expectedExceptionMessage  Server Request Error (Response Http Code: 500)
     */
    public function testResponseStatusCodeExceptionThrown()
    {
        $serverResponse = new \Sender\HttpRequest\ServerResponseDto(500, $this->validDummyXml);
        $stub = $this->createMock(\Sender\HttpRequest\HttpRequestInterface::class);
        $stub->method('request')
            ->willReturn($serverResponse);

        $this->service = new \Sender\AcmeSenderService(
            '11',
            'pass',
            $stub,
            new \Sender\Factories\AcmeApiXmlFactory()
        );
        $this->service->sendFoo(['job' => '1111', 'transaction' => 'aaa']);
    }

    /**
     * @expectedException        \Sender\Exceptions\ServerOperationException
     * @expectedExceptionMessage  Invalid Original Transaction ID
     */
    public function testServerSideErrorThrown()
    {
        $serverResponse = new \Sender\HttpRequest\ServerResponseDto(200, $this->validBarXmlResponse);
        $stub = $this->createMock(\Sender\HttpRequest\HttpRequestInterface::class);
        $stub->method('request')
            ->willReturn($serverResponse);

        $this->service = new \Sender\AcmeSenderService(
            '11',
            'pass',
            $stub,
            new \Sender\Factories\AcmeApiXmlFactory()
        );
        $this->service->sendFoo(['job' => '1111', 'transaction' => 'aaa']);
    }

    public function testServerSuccessBar()
    {
        $successResult = [
            'code' => 'success',
            'description' => 'Some descr',
            'result' => 'approved',
            'order' => 'number',
            'transaction' => 'some',
            'bar' => '123',
        ];
        $serverResponse = new \Sender\HttpRequest\ServerResponseDto(200, $this->successBarXmlResponse);
        $stub = $this->createMock(\Sender\HttpRequest\HttpRequestInterface::class);
        $stub->method('request')
            ->willReturn($serverResponse);

        $this->service = new \Sender\AcmeSenderService(
            '11',
            'pass',
            $stub,
            new \Sender\Factories\AcmeApiXmlFactory()
        );
        $result = $this->service->sendFoo(['job' => '1111', 'transaction' => 'aaa']);
        $this->assertEquals($result, $successResult);
    }
}