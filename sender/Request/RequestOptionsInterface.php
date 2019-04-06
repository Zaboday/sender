<?php

namespace Sender\Request;

/**
 * Class RequestOptionsInterface
 *
 * @package Sender\HttpClient
 */
interface RequestOptionsInterface
{

    /**
     * Body of request to Server (xml/json...)
     *
     * @return string
     */
    public function getBody(): string;

    /**
     * Account Credentials add other Operation data
     *
     * @param array $requestData
     */
    public function setRequestData(array $requestData): void;

    /**
     * Headers for request to Server
     *
     * @return array
     */
    public function getHeaders(): array;

    /**
     * Url for request to Server
     *
     * @return string
     */
    public function getUrl(): string;

    /**
     * Http method for request to Server
     *
     * @return string
     */
    public function getHttpMethod(): string;

    /**
     * Convert Server response (xml or json) to array data
     *
     * @param string $response
     *
     * @return array
     */
    public function convertResponseToArr(string $response): array;
}