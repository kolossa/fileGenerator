<?php


namespace Language;


class ApiCallAdapter implements IApiCall
{
    public function call(string $target, string $mode, array $getParameters, array $postParameters): ?array
    {
        return ApiCall::call($target, $mode, $getParameters, $postParameters);
    }
}