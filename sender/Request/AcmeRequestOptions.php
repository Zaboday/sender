<?php

namespace Sender\Request;

use Sender\Converters\ConverterInterface;

/**
 * Class RequestOptions.
 * Содержит данные (метод, заголовки, урл) для запроса в Acme Api
 *
 * @package Sender\HttpClient
 */
class AcmeRequestOptions implements RequestOptionsInterface
{
    // <Root>....</Root>
    public const ROOT_DATA_REQUEST_KEY = 'Root';

    // Account credential field names
    public const ACCOUNT_DATA_KEY = 'Password';
    public const PASSWORD_DATA_KEY = 'Account';

    /**
     * Endpoint
     *
     * @var string
     */
    private $url;

    /**
     * Data to send.
     *
     * @var array
     */
    private $headers = [];

    /**
     * GET/POST
     *
     * @var string
     */
    private $httpMethod;

    /**
     * Конвертер. Для предоставления серверу данных в нужном формате
     *
     * @var ConverterInterface;
     */
    private $dataConverter;

    /**
     * @var array
     */
    private $requestData = [];

    public function __construct(ConverterInterface $dataConverter)
    {
        $this->dataConverter = $dataConverter;
    }

    /**
     * Body of request
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->dataConverter->convertTo(self::ROOT_DATA_REQUEST_KEY, $this->requestData);
    }

    /**
     * @param array $requestData
     */
    public function setRequestData(array $requestData): void
    {
        $this->requestData = $requestData;
    }

    /**
     * Adds header
     *
     * @param string $header
     */
    public function addHeader(string $header): void
    {
        if (in_array($header, $this->headers, true)) {
            return;
        }
        $this->headers[] = $header;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url): void
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    /**
     * @param string $httpMethod
     */
    public function setHttpMethod($httpMethod): void
    {
        $this->httpMethod = $httpMethod;
    }

    /**
     * Converts Response to array
     *
     * @param string $response
     *
     * @return array
     */
    public function convertResponseToArr(string $response): array
    {
        return $this->dataConverter->convertFrom($response);
    }
}