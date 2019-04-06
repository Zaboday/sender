<?php

namespace Sender\HttpRequest;

class ServerResponseDto
{
    /**
     * @var string
     */
    public $content;

    /**
     * @var int
     */
    public $httpCode;

    public function __construct(int $httpCode, string $content)
    {
        $this->httpCode = $httpCode;
        $this->content = $content;
    }
}