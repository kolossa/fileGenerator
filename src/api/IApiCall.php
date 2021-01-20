<?php


namespace Language\api;

/**
 * Interface IApiCall
 * @package Language
 */
interface IApiCall
{
    /**
     * @param string $target
     * @param string $mode
     * @param array $getParameters
     * @param array $postParameters
     * @return ApiCallDTO
     */
    public function call(string $target, string $mode, array $getParameters, array $postParameters): ApiCallDTO;
}