<?php

namespace Sender;

interface SenderServiceInterface
{
    public function sendBar(array $data): array;

    public function sendFoo(array $data): array;
}