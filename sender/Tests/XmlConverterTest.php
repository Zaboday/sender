<?php

use PHPUnit\Framework\TestCase;
use Sender\Converters\XmlConverter;

class XmlConverterTest extends TestCase
{
    /**
     * @var XmlConverter
     */
    private $converter;

    private $startTag = 'payload';
    private $from = ['foo' => 1, 'bar' => 2, 3 => 'Some text'];
    private $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<payload><foo>1</foo><bar>2</bar><item3>Some text</item3></payload>\n";

    public function setUp()
    {
        $this->converter = new XmlConverter();
    }

    public function tearDown()
    {
        $this->converter = null;
    }

    public function testConvertToXml()
    {
        $this->assertEquals(
            $this->converter->convertTo($this->startTag, $this->from),
            $this->xml
        );
    }

    public function testConvertFrom()
    {
        $from = $this->from;
        unset($from[3]);
        $from['item3'] = $this->from[3];
        $this->assertEquals(
            $this->converter->convertFrom($this->xml),
            $from
        );
    }

    /**
     * @expectedException        \Sender\Exceptions\ServerResponseException
     * @expectedExceptionMessage Conversion to XML is failed. String could not be parsed as XML
     */
    public function testExceptionRisedConvertTo()
    {
        $emptyStartTag = '';
        $this->assertEquals(
            $this->converter->convertTo($emptyStartTag, $this->from),
            $this->xml
        );
    }

    /**
     * @expectedException        \Sender\Exceptions\ServerResponseException
     * @expectedExceptionMessage  Can't convert from XML to data. Invalid XML format.
     */
    public function testExceptionRisedConvertFrom()
    {
        $this->assertEquals(
            $this->converter->convertFrom(''),
            $this->xml
        );
    }
}