<?php


namespace Language;

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
     * @return array|null
     */
    public function call(string $target, string $mode, array $getParameters, array $postParameters): ?array;
}