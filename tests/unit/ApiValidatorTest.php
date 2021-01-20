<?php


namespace tests\unit;

use Language\api\ApiCallDTO;
use Language\api\ApiValidator;
use Language\api\exceptions\ApiCallErrorException;
use Language\api\exceptions\ApiCallWrongContentException;
use Language\api\exceptions\ApiCallWrongResponseException;
use PHPUnit\Framework\TestCase;

class ApiValidatorTest extends TestCase
{
    /**
     * @test
     */
    public function nullApiResult()
    {
        $result=new ApiCallDTO(null, null, null);

        $apiValidator = new ApiValidator();
        $this->expectException(ApiCallErrorException::class);
        $apiValidator->checkForApiErrorResult($result);
    }

    /**
     * @test
     */
    public function wrongApiStatus()
    {
        $result=new ApiCallDTO('error', 'test', null);

        $apiValidator = new ApiValidator();
        $this->expectException(ApiCallWrongResponseException::class);
        $apiValidator->checkForApiErrorResult($result);
    }

    /**
     * @test
     */
    public function wrongApiContent()
    {
        $result=new ApiCallDTO('OK', false, null);

        $apiValidator = new ApiValidator();
        $this->expectException(ApiCallWrongContentException::class);
        $apiValidator->checkForApiErrorResult($result);
    }

    /**
     * @test
     */
    public function fine()
    {
        $result=new ApiCallDTO('OK', 'test', null);

        $apiValidator = new ApiValidator();
        $apiValidator->checkForApiErrorResult($result);
        $this->assertTrue(true);
    }
}