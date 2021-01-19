<?php


namespace Language;


class ApiCallAdapter implements IApiCall
{
    const TARGET = 'system_api';
    const MODE = 'language_api';

    public function call(string $target, string $mode, array $getParameters, array $postParameters): ApiCallDTO
    {
        $result = ApiCall::call($target, $mode, $getParameters, $postParameters);
        $status = $result['status'] ?? null;
        $data = $result['data'] ?? null;
        $errorType = $result['error_type'] ?? null;

        return new ApiCallDTO($status, $data, $errorType);
    }
}