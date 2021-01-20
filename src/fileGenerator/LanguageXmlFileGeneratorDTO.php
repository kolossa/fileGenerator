<?php


namespace Language\FileGenerator;


use Language\api\ApiValidator;
use Language\api\IApiCall;
use Language\ILogger;

class LanguageXmlFileGeneratorDTO
{
    private string $systemPathRoot;
    private IApiCall $apiCall;
    private ApiValidator $apiValidator;

    /**
     * List of the applets [directory => applet_id].
     * @var array $applets
     */
    private array $applets;
    private ILogger $logger;

    /**
     * LanguageXmlFileGeneratorDTO constructor.
     * @param string $systemPathRoot
     * @param IApiCall $apiCall
     * @param ApiValidator $apiValidator
     * @param array $applets
     * @param ILogger $logger
     */
    public function __construct(
        string $systemPathRoot,
        IApiCall $apiCall,
        ApiValidator $apiValidator,
        array $applets,
        ILogger $logger
    ) {
        $this->systemPathRoot = $systemPathRoot;
        $this->apiCall = $apiCall;
        $this->apiValidator = $apiValidator;
        $this->applets = $applets;
        $this->logger = $logger;
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
     * @return array
     */
    public function getApplets(): array
    {
        return $this->applets;
    }

    /**
     * @return ILogger
     */
    public function getLogger(): ILogger
    {
        return $this->logger;
    }


}