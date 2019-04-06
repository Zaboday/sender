<?php

use PHPUnit\Framework\TestCase;
use Sender\Converters\XmlConverter;

class RequestOptionsTest extends TestCase
{
    /**
     * @var \Sender\Request\AcmeRequestOptions
     */
    private $requestOptions;

    private $startTag = 'payload';
    private $from = ['foo' => 1, 'bar' => 2, 'barBar' => 'Some text'];
    private $xml;

    public function setUp()
    {
        $rootTag = \Sender\Request\AcmeRequestOptions::ROOT_DATA_REQUEST_KEY;
        $this->xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<$rootTag><foo>1</foo><bar>2</bar><barBar>Some text</barBar></$rootTag>\n";
        $this->requestOptions = new \Sender\Request\AcmeRequestOptions(new XmlConverter());
    }

    public function tearDown()
    {
        $this->requestOptions = null;
    }

    public function testHeaders()
    {
        $this->requestOptions->addHeader('one: some1');
        $this->requestOptions->addHeader('two: some2');
        $this->requestOptions->addHeader('one: some1');
        $this->assertEquals($this->requestOptions->getHeaders(),
            ['one: some1', 'two: some2']
        );
        $this->requestOptions->setHeaders([]);
        $this->assertEquals($this->requestOptions->getHeaders(),
            []
        );
    }

    public function testBodyAndRequestData()
    {
        // Body without data
        $rootTag = \Sender\Request\AcmeRequestOptions::ROOT_DATA_REQUEST_KEY;
        $this->assertEquals($this->requestOptions->getBody(), "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<$rootTag/>\n");
        // Body with data
        $this->requestOptions->setRequestData($this->from);
        $this->assertEquals($this->requestOptions->getBody(), $this->xml);
    }

    public function testGetters()
    {
        $this->requestOptions->setUrl('some');
        $this->assertEquals($this->requestOptions->getUrl(), 'some');

        $this->requestOptions->setHttpMethod('PATCH');
        $this->assertEquals($this->requestOptions->getHttpMethod(), 'PATCH');
    }

    public function testConvertResponse() {
        $this->assertEquals(
            $this->requestOptions->convertResponseToArr($this->xml),
            $this->from
        );
    }
}