<?php

namespace Sender\HttpRequest;

use Sender\Request\RequestOptionsInterface;

interface HttpRequestInterface
{
    public const METHOD_POST = 'POST';
    public const METHOD_GET = 'GET';

    public const CODE_SUCCESS = 200;

    /**
     * Make HTTP request.
     *
     * @param RequestOptionsInterface $options
     *
     * @return ServerResponseDto
     */
    public function request(RequestOptionsInterface $options): ServerResponseDto;
}