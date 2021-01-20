<?php


namespace Language\FileGenerator;


use Language\api\ApiValidator;
use Language\api\IApiCall;
use Language\ILogger;

class LanguageFileGeneratorDTO
{
    /**
     * Contains the applications which ones require translations.
     *
     * @var array
     */
    private array $applications = [];
    private string $systemPathRoot;
    private IApiCall $apiCall;
    private ApiValidator $apiValidator;
    private ILogger $logger;

    /**
     * LanguageFileGeneratorDTO constructor.
     * @param array $applications
     * @param string $systemPathRoot
     * @param IApiCall $apiCall
     * @param ApiValidator $apiValidator
     * @param ILogger $logger
     */
    public function __construct(
        array $applications,
        string $systemPathRoot,
        IApiCall $apiCall,
        ApiValidator $apiValidator,
        ILogger $logger
    ) {
        $this->applications = $applications;
        $this->systemPathRoot = $systemPathRoot;
        $this->apiCall = $apiCall;
        $this->apiValidator = $apiValidator;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public function getApplications(): array
    {
        return $this->applications;
    }

    /**
     * @return string
     */
    public function getSystemPathRoot(): string
    {
        return $this->systemPathRoot;
    }

    /**
     * @return IApiCall
     */
    public function getApiCall(): IApiCall
    {
        return $this->apiCall;
    }

    /**
     * @return ApiValidator
     */
    public function getApiValidator(): ApiValidator
    {
        return $this->apiValidator;
    }

    /**
     * @return ILogger
     */
    public function getLogger(): ILogger
    {
        return $this->logger;
    }


}