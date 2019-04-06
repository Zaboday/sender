<?php

namespace Sender\Response;

/**
 * Class JobOneResponseValidator
 * Валидатор ответа сервера в метод "Foo"
 *
 * @package Sender\Resources
 */
class AcmeFooResponseValidator extends AcmeResponseValidator
{
    /**
     * Required data for this resource
     *
     * @var array
     */
    protected $responseRequiredForResource = [
        'job', 'transaction', 'order',
    ];
}