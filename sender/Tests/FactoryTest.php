<?php

use PHPUnit\Framework\TestCase;
use Sender\Converters\XmlConverter;

class FactoryTest extends TestCase
{
    /**
     * @var \Sender\Factories\AcmeApiXmlFactory
     */
    private $acmeFactory;

    /**
     * @var string
     */
    private $xml;

    public function setUp()
    {
        $this->acmeFactory = new \Sender\Factories\AcmeApiXmlFactory();
        $rootTag = \Sender\Request\AcmeRequestOptions::ROOT_DATA_REQUEST_KEY;
        $this->xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<$rootTag><foo>bar</foo><bar>foo</bar></$rootTag>\n";
    }

    public function tearDown()
    {
        $this->acmeFactory = null;
    }

    public function testAcmeBarRequestOptions()
    {
        $options = $this->acmeFactory->createBarRequestOptions('/some', ['foo' => 'bar', 'bar' => 'foo']);
        $this->assertInstanceOf(\Sender\Request\AcmeRequestOptions::class, $options);

        $this->assertEquals($options->getUrl(), '/some');
        $this->assertEquals($options->getHttpMethod(), 'POST');
        $this->assertEquals($options->getBody(), $this->xml);
    }

    public function testAcmeFooRequestOptions()
    {
        $options = $this->acmeFactory->createFooRequestOptions('/some-token', ['foo' => 'bar', 'bar' => 'foo']);
        $this->assertInstanceOf(\Sender\Request\AcmeRequestOptions::class, $options);

        $this->assertEquals($options->getUrl(), '/some-token');
        $this->assertEquals($options->getHttpMethod(), 'POST');
        $this->assertEquals($options->getBody(), $this->xml);
    }

    public function testAcmeBarResponseValidator()
    {
        $validator = $this->acmeFactory->createBarResponseValidator();
        $this->assertInstanceOf(\Sender\Response\AcmeBarResponseValidator::class, $validator);
    }

    public function testAcmeFooResponseValidator()
    {
        $validator = $this->acmeFactory->createFooResponseValidator();
        $this->assertInstanceOf(\Sender\Response\AcmeFooResponseValidator::class, $validator);
    }
}