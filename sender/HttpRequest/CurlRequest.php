<?php

namespace Sender\HttpRequest;

use Sender\Request\RequestOptionsInterface;

class CurlRequest implements HttpRequestInterface
{
    /**
     * Make HTTP request
     *
     * @param RequestOptionsInterface $options
     *
     * @return ServerResponseDto
     * @throws \RuntimeException
     */
    public function request(RequestOptionsInterface $options): ServerResponseDto
    {
        $opts[CURLOPT_HTTPHEADER] = $options->getHeaders();

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $options->getHeaders());
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $options->getUrl());

        if ($options->getHttpMethod() === HttpRequestInterface::METHOD_POST) {
            curl_setopt($curl, CURLOPT_POST, 1);
        }

        curl_setopt($curl, CURLOPT_POSTFIELDS, $options->getBody());

        $content = curl_exec($curl);

        if ($content === false) {
            $errorNumber = curl_errno($curl);
            $message = curl_error($curl);
            curl_close($curl);
            $message = "(Network error Curl [errno $errorNumber]: $message)";

            throw new \RuntimeException($message);
        }

        $rCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);

        return new ServerResponseDto($rCode, $content);
    }
}