<?php

namespace Sender\Response;

interface ResponseValidatorInterface
{
    public function validateResponse(int $httpStatusCode, array $response): void;
}