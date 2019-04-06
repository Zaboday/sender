<?php


namespace Sender\Response;

use Sender\Exceptions\ServerResponseException;
use Sender\Exceptions\ServerOperationException;
use Sender\Utils\Utils;

/**
 * Class AcmeResponseValidator. Класс описывающий Запрос и ответ от api
 *
 * @package Sender\Resources
 */
abstract class AcmeResponseValidator implements ResponseValidatorInterface
{
    public const HTTP_CODE_SUCCESS = 200;

    /**
     * Required fields in response from Server for all resources
     */
    public const RESPONSE_CODE = 'code';
    public const RESPONSE_DESC = 'description';
    public const RESPONSE_RESULT = 'result';

    // Successful Transaction
    public const CODE_SUCCESS = 'success';
    public const SUCCESS_VALUE = 'approved';

    public const CODE_ERRORS = [
        'A101' => 'Missing Account ID',
        'A102' => 'Missing Password',
        'A103' => 'Missing Type',
        'A104' => 'Missing Order ID',
        'A119' => 'Invalid Type',
        'A121' => 'Invalid Currency',
        'A123' => 'Invalid Service Type',
        'A124' => 'Invalid Method',
        'A125' => 'Invalid Original Transaction ID',
        'B212' => 'Invalid transaction',
        'B213' => 'Invalid amount',
        'B214' => 'Invalid object number',
        'B291' => 'Issuer unavailable or switch inoperative',
        'B292' => 'Unable to route transaction',
        'B296' => 'System error',
        'Y59999' => 'Third service error',
        'Y99991' => 'Invalid Object details. Please check Object values.',
        'Z99999' => 'Timeout',
    ];

    /**
     * Required data for the Resource.
     *
     * @var array
     */
    protected $responseRequiredForResource = [];

    /**
     * Validate Server Response
     *
     * @param int $httpStatusCode
     * @param array $response
     *
     * @throws ServerResponseException
     * @throws ServerOperationException
     */
    public function validateResponse(int $httpStatusCode, array $response): void
    {
        $this->validateResponseHttpStatusCode($httpStatusCode);
        $this->validateResponseFormat($response);
        $this->validateResponseServerStatusCode($response);
        $this->validateFieldsForResource($response);
    }

    /**
     * Validate Http Status Code
     *
     * @param $httpCode
     *
     * @throws ServerResponseException
     */
    private function validateResponseHttpStatusCode($httpCode): void
    {
        if ($httpCode !== self::HTTP_CODE_SUCCESS) {
            throw new ServerResponseException('Server Request Error (Response Http Code: ' . $httpCode . ')');
        }
    }

    /**
     * Проверка необходимх полей в ответе общих для всех запросов
     *
     * @param array $response
     *
     * @throws ServerResponseException
     */
    private function validateResponseFormat(array $response): void
    {
        $diff = array_diff($this->responseRequiredFieldsForAllResponses(), array_keys($response));
        if ($diff) {
            throw new ServerResponseException('Invalid Format Server Response. Missing fields: '.implode(', ', $diff));
        }
    }

    /**
     * Проверка кода ответа сервера
     *
     * @param $response
     *
     * @throws ServerOperationException
     */
    private function validateResponseServerStatusCode($response): void
    {
        $serverCode = $response[self::RESPONSE_CODE];
        $serverDescription = $response[self::RESPONSE_DESC];
        if ($serverCode !== self::CODE_SUCCESS) {
            $message = empty(self::CODE_ERRORS[$serverCode]) ? $serverDescription : self::CODE_ERRORS[$serverCode];
            throw new ServerOperationException($message);
        }

        if ($response[self::RESPONSE_RESULT] !== self::SUCCESS_VALUE) {

            throw new ServerOperationException($serverDescription);
        }
    }

    /**
     * Проверка необходимых полей в ответе именно для этого запроса
     *
     * @param array $response
     *
     * @throws ServerResponseException
     */
    private function validateFieldsForResource(array $response): void
    {
        $diff = array_diff($this->responseRequiredForResource, array_keys($response));
        if ($diff) {
            $message = 'Missing data in server response for ' .
                Utils::className(get_class($this)) . ': ' . implode(', ', $diff);
            throw new ServerResponseException($message);
        }
    }

    /**
     * Get required fields in response from Server for all resources
     *
     * @return array
     */
    private function responseRequiredFieldsForAllResponses(): array
    {
        return [
            self::RESPONSE_CODE,
            self::RESPONSE_DESC,
            self::RESPONSE_RESULT,
        ];
    }
}