<?php


namespace Language;


class ApiCallAdapter implements IApiCall
{
    const TARGET = 'system_api';
    const MODE = 'language_api';

    public function call(string $target, string $mode, array $getParameters, array $postParameters): ?array
    {
        return ApiCall::call($target, $mode, $getParameters, $postParameters);
    }
}