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
    public function checkForApiErrorResult(ApiCallDTO $result): void
    {
        // Error during the api call.
        if ($result->getStatus()==null) {
            throw new ApiCallErrorException('Error during the api call');
        }
        // Wrong response.
        if ($result->getStatus() != 'OK') {
            throw new ApiCallWrongResponseException(
                'Wrong response: '
                . (!empty($result->getErrorType()) ? 'Type(' . $result->getErrorType() . ') ' : '')
                . (!empty($result->getErrorType()) ? 'Code(' . $result->getErrorType() . ') ' : '')
                . ((string)$result->getData())
            );
        }
        // Wrong content.
        if ($result->getData() === false) {
            throw new ApiCallWrongContentException('Wrong content!');
        }
    }
}