<?php

namespace Sender\Factories;

use Sender\Request\RequestOptionsInterface;
use Sender\Response\ResponseValidatorInterface;

interface ApiFactoryInterface
{
    public function createFooRequestOptions(string $endPoint, array $data): RequestOptionsInterface;

    public function createBarRequestOptions(string $endPoint, array $data): RequestOptionsInterface;

    public function createFooResponseValidator(): ResponseValidatorInterface;

    public function createBarResponseValidator(): ResponseValidatorInterface;
}