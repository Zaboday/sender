<?php

namespace Sender;

use Sender\Factories\ApiFactoryInterface;
use Sender\HttpRequest\HttpRequestInterface;
use Psr\Log\LoggerInterface;
use Sender\Request\AcmeRequestOptions;

/**
 * Class AcmeService. Main service
 *
 * @package AcmeSenderService
 */
class AcmeSenderService implements SenderServiceInterface
{
    /**
     * Credentials
     *
     * @var string
     */
    private $account;

    /**
     * Credentials
     *
     * @var string
     */
    private $password;

    /**
     * @var HttpRequestInterface
     */
    private $httpRequester;

    /**
     * @var ApiFactoryInterface
     */
    private $factory;

    /**
     * @var string
     */
    private $baseUrl = 'https://httpbin.org';

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        $account,
        $password,
        HttpRequestInterface $httpRequester,
        ApiFactoryInterface $factory,
        LoggerInterface $logger = null)
    {
        $this->logger = $logger;
        $this->factory = $factory;
        $this->account = $account;
        $this->password = $password;
        $this->httpRequester = $httpRequester;
    }

    /**
     * "Bar" request
     *
     * @param array $data
     *
     * @return array
     */
    public function sendBar(array $data): array
    {
        $request = $this->factory->createFooRequestOptions(
            $this->baseUrl . '/anything',
            $this->addAccountCredentials($data)
        );
        $validator = $this->factory->createFooResponseValidator();
        $serverResponse = $this->httpRequester->request($request);
        $response = $request->convertResponseToArr($serverResponse->content);
        $validator->validateResponse($serverResponse->httpCode, $response);

        return $response;
    }

    /**
     * Make "Foo" request
     *
     * @param array $data
     *
     * @return array
     */
    public function sendFoo(array $data): array
    {
        $request = $this->factory->createBarRequestOptions(
            $this->baseUrl . '/anything',
            $this->addAccountCredentials($data)
        );
        $validator = $this->factory->createBarResponseValidator();
        $serverResponse = $this->httpRequester->request($request);
        $response = $request->convertResponseToArr($serverResponse->content);
        $validator->validateResponse($serverResponse->httpCode, $response);

        return $response;
    }

    /**
     * Add account/pass
     *
     * @param array $data
     *
     * @return array
     */
    private function addAccountCredentials(array $data): array
    {
        $data[AcmeRequestOptions::ROOT_DATA_REQUEST_KEY][AcmeRequestOptions::ACCOUNT_DATA_KEY] = $this->account;
        $data[AcmeRequestOptions::ROOT_DATA_REQUEST_KEY][AcmeRequestOptions::PASSWORD_DATA_KEY] = $this->password;

        return $data;
    }

    /**
     * Log error
     *
     * @param string $message
     */
    private function logError(string $message)
    {
        if (!$this->logger) {
            return;
        }
        $this->logger->error($message);
    }

    /**
     * Log Info
     *
     * @param string $message
     */
    private function logInfo(string $message)
    {
        if (!$this->logger) {
            return;
        }
        $this->logger->info($message);
    }
}