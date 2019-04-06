<?php


namespace Sender\Factories;

use Sender\Converters\XmlConverter;
use Sender\HttpRequest\HttpRequestInterface;
use Sender\Request\AcmeRequestOptions;
use Sender\Request\RequestOptionsInterface;
use Sender\Response\AcmeFooResponseValidator;
use Sender\Response\AcmeBarResponseValidator;
use Sender\Response\ResponseValidatorInterface;

/**
 * Class AcmeApiXmlFactory.
 * Фабрика для xml запросов в Acme api
 *
 * @package Sender\Factories
 */
class AcmeApiXmlFactory implements ApiFactoryInterface
{
    private $headers = [
        'Accept: application/xml',
        'Content-Type: application/xml',
    ];

    /**
     * @param string $endPoint
     * @param array $data
     *
     * @return RequestOptionsInterface
     */
    public function createFooRequestOptions(string $endPoint, array $data): RequestOptionsInterface
    {
        return $this->createRequestOptions($endPoint, HttpRequestInterface::METHOD_POST, $data);
    }

    /**
     * @param string $endPoint
     * @param array $data
     *
     * @return RequestOptionsInterface
     */
    public function createBarRequestOptions(string $endPoint, array $data): RequestOptionsInterface
    {
        return $this->createRequestOptions($endPoint, HttpRequestInterface::METHOD_POST, $data);
    }

    /**
     * Makes Foo Response Validator
     *
     * @return AcmeFooResponseValidator
     */
    public function createFooResponseValidator(): ResponseValidatorInterface
    {
        return new AcmeFooResponseValidator();
    }

    /**
     * Makes Bar Response Validator
     *
     * @return AcmeBarResponseValidator
     */
    public function createBarResponseValidator(): ResponseValidatorInterface
    {
        return new AcmeBarResponseValidator();
    }

    /**
     * @param string $url
     *
     * @param string $httpMethod
     * @param array $data
     *
     * @return RequestOptionsInterface
     */
    private function createRequestOptions(string $url, string $httpMethod, array $data): RequestOptionsInterface
    {
        $requestOptions = new AcmeRequestOptions(new XmlConverter());
        $requestOptions->setHttpMethod($httpMethod);
        $requestOptions->setRequestData($data);
        foreach ($this->headers as $header) {
            $requestOptions->addHeader($header);
        }
        $requestOptions->setUrl($url);

        return $requestOptions;
    }
}