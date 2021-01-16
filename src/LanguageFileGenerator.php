<?php


namespace Language;


class LanguageFileGenerator implements ILanguageFileGenerator
{
    /**
     * Contains the applications which ones require translations.
     *
     * @var array
     */
    protected array $applications = array();
    protected string $systemPathRoot;
    protected IApiCall $apiCall;
    protected ApiValidator $apiValidator;

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


        echo "\nGenerating language files\n";
        foreach ($this->applications as $application => $languages) {
            echo "[APPLICATION: " . $application . "]\n";
            foreach ($languages as $language) {
                echo "\t[LANGUAGE: " . $language . "]";
                if ($this->getLanguageFile($application, $language)) {
                    echo " OK\n";
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
    protected function getLanguageFile($application, $language): bool
    {
        $result = false;
        $languageResponse = $this->apiCall->call(
            'system_api',
            'language_api',
            array(
                'system' => 'LanguageFiles',
                'action' => 'getLanguageFile'
            ),
            array('language' => $language)
        );

        try {
            $this->apiValidator->checkForApiErrorResult($languageResponse);
        } catch (\Exception $e) {
            throw new \Exception('Error during getting language file: (' . $application . '/' . $language . ')');
        }

        // If we got correct data we store it.
        $destination = $this->getLanguageCachePath($application) . $language . '.php';
        // If there is no folder yet, we'll create it.
        var_dump($destination);
        if (!is_dir(dirname($destination))) {
            mkdir(dirname($destination), 0755, true);
        }

        $result = file_put_contents($destination, $languageResponse['data']);

        return (bool)$result;
    }

    /**
     * Gets the directory of the cached language files.
     *
     * @param string $application The application.
     *
     * @return string   The directory of the cached language files.
     */
    protected function getLanguageCachePath($application)
    {
        return $this->systemPathRoot . '/cache/' . $application . '/';
    }
}