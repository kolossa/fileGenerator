<?php


namespace Language;


use Language\exceptions\ApiCallErrorException;
use Language\exceptions\ApiCallWrongContentException;
use Language\exceptions\ApiCallWrongResponseException;

class ApiValidator
{
    /**
     * Checks the api call result.
     *
     * @param mixed $result The api call result to check.
     *
     * @return void
     *
     * @throws ApiCallErrorException
     * @throws ApiCallWrongContentException
     * @throws ApiCallWrongResponseException
     */
    public function checkForApiErrorResult($result): void
    {
        // Error during the api call.
        if ($result === false || !isset($result['status'])) {
            throw new ApiCallErrorException('Error during the api call');
        }
        // Wrong response.
        if ($result['status'] != 'OK') {
            throw new ApiCallWrongResponseException(
                'Wrong response: '
                . (!empty($result['error_type']) ? 'Type(' . $result['error_type'] . ') ' : '')
                . (!empty($result['error_code']) ? 'Code(' . $result['error_code'] . ') ' : '')
                . ((string)$result['data'])
            );
        }
        // Wrong content.
        if ($result['data'] === false) {
            throw new ApiCallWrongContentException('Wrong content!');
        }
    }
}