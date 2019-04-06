<?php

namespace Sender\Converters;

interface ConverterInterface
{
    /**
     * Converts data array to something (xml/json) for request
     *
     * @param string $startKey
     * @param array $data
     *
     * @return string
     */
    public function convertTo(string $startKey, array $data): string;

    /**
     * Converts response from requests to array
     *
     * @param string $string
     *
     * @return array
     */
    public function convertFrom($string): array;
}