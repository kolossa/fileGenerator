<?php


namespace tests\integration;


use Language\api\ApiCallDTO;

class FakeApiCall implements \Language\api\IApiCall
{
    public function call(string $target, string $mode, array $getParameters, array $postParameters): ApiCallDTO
    {
        return new ApiCallDTO('OK', 'test', null);
    }
}