<?php


namespace Language;


class LanguageFileGenerator implements ILanguageFileGenerator
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
     * LanguageFileGenerator constructor.
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
     * Starts the language file generation.
     *
     * @return void
     */
    public function generateLanguageFiles(): void
    {
        // The applications where we need to translate.

        $this->logger->info('Generating language files');
        foreach ($this->applications as $application => $languages) {
            $this->generateLanguageFile($application, $languages);
        }
        $this->logger->info('Generating language files - end');
    }

    private function generateLanguageFile(string $application, array $languages): void
    {
        $this->logger->info('[APPLICATION: ' . $application . '] started');
        foreach ($languages as $language) {
            $this->logger->info('[APPLICATION: ' . $application . '][LANGUAGE: ' . $language . ']');
            if ($this->getLanguageFile($application, $language)) {
                $this->logger->info('[APPLICATION: ' . $application . '][LANGUAGE: ' . $language . '] OK');
            } else {
                throw new \Exception('Unable to generate language file!');
            }
        }
    }

    /**
     * Gets the language file for the given language and stores it.
     *
     * @param string $application The name of the application.
     * @param string $language The identifier of the language.
     *
     * @return bool   The success of the operation.
     * @throws CurlException   If there was an error during the download of the language file.
     *
     */
    private function getLanguageFile(string $application, string $language): bool
    {
        $languageResponse = $this->getValidFileContent($application, $language);
        // If we got correct data we store it.
        $path = $this->getLanguageCachePath($application);
        if (is_dir($path) && !is_writable($path)) {
            throw new \Exception($path . ' is not writable!');
        }
        $destination = $path . $language . '.php';
        // If there is no folder yet, we'll create it.
        $this->logger->info('[APPLICATION: ' . $application . '][LANGUAGE: ' . $language . '] ' . $destination);
        if (!is_dir(dirname($destination))) {
            mkdir(dirname($destination), 0755, true);
        }

        if (file_put_contents($destination, $languageResponse['data']) === false) {
            return false;
        }

        return true;
    }

    private function getValidFileContent(string $application, string $language): array
    {
        $languageResponse = $this->apiCall->call(
            ApiCallAdapter::TARGET,
            ApiCallAdapter::MODE,
            [
                'system' => 'LanguageFiles',
                'action' => 'getLanguageFile'
            ],
            ['language' => $language]
        );

        try {
            $this->apiValidator->checkForApiErrorResult($languageResponse);
        } catch (\Exception $e) {
            throw new \Exception(
                'Error during getting language file: (' . $application . '/' . $language . '): ' . $e->getMessage()
            );
        }

        return $languageResponse;
    }

    /**
     * Gets the directory of the cached language files.
     *
     * @param string $application The application.
     *
     * @return string   The directory of the cached language files.
     */
    private function getLanguageCachePath(string $application): string
    {
        return $this->systemPathRoot . '/cache/' . $application . '/';
    }
}