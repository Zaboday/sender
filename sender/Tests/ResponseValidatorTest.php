<?php

use PHPUnit\Framework\TestCase;
use Sender\Converters\XmlConverter;

class ResponseValidatorTest extends TestCase
{
    /**
     * @var \Sender\Response\AcmeBarResponseValidator
     */
    private $barValidator;
    /**
     * @var \Sender\Response\AcmeFooResponseValidator
     */
    private $fooValidator;

    public function setUp()
    {
        $this->barValidator = new \Sender\Response\AcmeBarResponseValidator();
        $this->fooValidator = new \Sender\Response\AcmeFooResponseValidator();
    }

    public function tearDown()
    {
        $this->barValidator = null;
    }

    /**
     * @expectedException        \Sender\Exceptions\ServerResponseException
     * @expectedExceptionMessage Server Request Error (Response Http Code: 500)
     */
    public function testInvalidHttpCodeException()
    {
        $this->barValidator->validateResponse(500, []);
    }

    /**
     * @expectedException        \Sender\Exceptions\ServerOperationException
     * @expectedExceptionMessage Message from server
     */
    public function testErrorStatusCodeServereSide()
    {
        $this->barValidator->validateResponse(200, [
            'code' => 'invalidcode',
            'description' => 'Message from server',
            'result' => 'result',
        ]);
    }

    /**
     * @expectedException        \Sender\Exceptions\ServerOperationException
     * @expectedExceptionMessage Invalid Currency
     */
    public function testPredefinedErrorStatusCodeServereSide()
    {
        $this->barValidator->validateResponse(200, [
            'code' => 'A121',
            'description' => 'Message from server',
            'result' => 'result',
        ]);
    }

    /**
     * @expectedException        \Sender\Exceptions\ServerResponseException
     * @expectedExceptionMessage Missing data in server response for AcmeBarResponseValidator: transaction,
     *                           bar
     */
    public function testRequiredBarResponseFormat()
    {
        $this->barValidator->validateResponse(200, [
            'code' => 'success',
            'description' => 'some description',
            'result' => 'approved',
            'order' => '111',
        ]);
    }


    public function testSuccessBarValidation()
    {
        $this->barValidator->validateResponse(200, [
            'code' => 'success',
            'description' => 'some description',
            'result' => 'approved',
            'order' => '111',
            'transaction' => '111',
            'bar' => '20',
        ]);
        $this->assertInstanceOf(\Sender\Response\AcmeBarResponseValidator::class, $this->barValidator);
    }

    public function testSuccessFoo()
    {
        $this->fooValidator->validateResponse(200, [
            'code' => 'success',
            'description' => 'some description',
            'result' => 'approved',
            'transaction' => 'some',
            'order' => 'some',
            'job' => 'some-some-some-some',
        ]);
        $this->assertInstanceOf(\Sender\Response\AcmeBarResponseValidator::class, $this->barValidator);
    }
}