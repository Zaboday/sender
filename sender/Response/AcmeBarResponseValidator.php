<?php

namespace Sender\Response;

class AcmeBarResponseValidator extends AcmeResponseValidator
{
    /**
     * Response data for this resource
     *
     * @var array
     */
    protected $responseRequiredForResource = [
        'order', 'transaction', 'bar',
    ];
}