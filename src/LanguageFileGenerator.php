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

    /**
     * LanguageFileGenerator constructor.
     * @param array $applications
     * @param string $systemPathRoot
     * @param IApiCall $apiCall
     * @param ApiValidator $apiValidator
     */
    public function __construct(
        array $applications,
        string $systemPathRoot,
        IApiCall $apiCall,
        ApiValidator $apiValidator
    ) {
        $this->applications = $applications;
        $this->systemPathRoot = $systemPathRoot;
        $this->apiCall = $apiCall;
        $this->apiValidator = $apiValidator;
    }


    /**
     * Starts the language file generation.
     *
     * @return void
     */
    public function generateLanguageFiles(): void
    {
        // The applications where we need to translate.


        echo PHP_EOL . 'Generating language files' . PHP_EOL;
        foreach ($this->applications as $application => $languages) {
            echo '[APPLICATION: ' . $application . ']' . PHP_EOL;
            foreach ($languages as $language) {
                echo "\t" . '[LANGUAGE: ' . $language . ']';
                if ($this->getLanguageFile($application, $language)) {
                    echo ' OK' . PHP_EOL;
                } else {
                    throw new \Exception('Unable to generate language file!');
                }
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
        $result = false;
        $languageResponse = $this->getValidFileContent($application, $language);
        // If we got correct data we store it.
        $destination = $this->getLanguageCachePath($application) . $language . '.php';
        // If there is no folder yet, we'll create it.
        echo $destination . ' ';
        if (!is_dir(dirname($destination))) {
            mkdir(dirname($destination), 0755, true);
        }

        $result = file_put_contents($destination, $languageResponse['data']);

        return (bool)$result;
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
            throw new \Exception('Error during getting language file: (' . $application . '/' . $language . ')');
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