<?php


namespace Language;

class LanguageXmlFileGenerator implements ILanguageXmlFileGenerator
{
    private string $systemPathRoot;
    private IApiCall $apiCall;
    private ApiValidator $apiValidator;

    /**
     * List of the applets [directory => applet_id].
     * @var array $applets
     */
    private array $applets;

    /**
     * LanguageXmlFileGenerator constructor.
     * @param string $systemPathRoot
     * @param IApiCall $apiCall
     * @param ApiValidator $apiValidator
     * @param array $applets
     */
    public function __construct(string $systemPathRoot, IApiCall $apiCall, ApiValidator $apiValidator, array $applets)
    {
        $this->systemPathRoot = $systemPathRoot;
        $this->apiCall = $apiCall;
        $this->apiValidator = $apiValidator;
        $this->applets = $applets;
    }


    public function generateAppletLanguageXmlFiles(): void
    {
        echo PHP_EOL . 'Getting applet language XMLs..' . PHP_EOL;

        foreach ($this->applets as $appletDirectory => $appletLanguageId) {
            echo ' Getting > ' . $appletLanguageId . ' (' . $appletDirectory . ') language xmls..' . PHP_EOL;
            $languages = $this->getAppletLanguages($appletLanguageId);
            if (empty($languages)) {
                throw new \Exception('There is no available languages for the ' . $appletLanguageId . ' applet.');
            } else {
                echo ' - Available languages: ' . implode(', ', $languages) . PHP_EOL;
            }
            $path = $this->getLanguageFlashPath();
            foreach ($languages as $language) {
                $xmlContent = $this->getAppletLanguageFile($appletLanguageId, $language);
                $xmlFile = $path . '/lang_' . $language . '.xml';
                if (strlen($xmlContent) == file_put_contents($xmlFile, $xmlContent)) {
                    echo ' OK saving ' . $xmlFile . ' was successful.' . PHP_EOL;
                } else {
                    throw new \Exception(
                        'Unable to save applet: (' . $appletLanguageId . ') language: (' . $language
                        . ') xml (' . $xmlFile . ')!'
                    );
                }
            }
            echo ' < ' . $appletLanguageId . ' (' . $appletDirectory . ') language xml cached.' . PHP_EOL;
        }

        echo PHP_EOL . 'Applet language XMLs generated.' . PHP_EOL;
    }

    private function getLanguageFlashPath(): string
    {
        return $this->systemPathRoot . '/cache/flash';
    }

    /**
     * Gets the available languages for the given applet.
     *
     * @param string $applet The applet identifier.
     *
     * @return array   The list of the available applet languages.
     */
    private function getAppletLanguages(string $applet)
    {
        $result = $this->apiCall->call(
            ApiCallAdapter::TARGET,
            ApiCallAdapter::MODE,
            [
                'system' => 'LanguageFiles',
                'action' => 'getAppletLanguages'
            ],
            ['applet' => $applet]
        );

        try {
            $this->apiValidator->checkForApiErrorResult($result);
        } catch (\Exception $e) {
            throw new \Exception('Getting languages for applet (' . $applet . ') was unsuccessful ' . $e->getMessage());
        }

        return $result['data'];
    }

    /**
     * Gets a language xml for an applet.
     *
     * @param string $applet The identifier of the applet.
     * @param string $language The language identifier.
     *
     * @return string|false   The content of the language file or false if weren't able to get it.
     */
    private function getAppletLanguageFile(string $applet, string $language)
    {
        $result = $this->apiCall->call(
            ApiCallAdapter::TARGET,
            ApiCallAdapter::MODE,
            [
                'system' => 'LanguageFiles',
                'action' => 'getAppletLanguageFile'
            ],
            [
                'applet' => $applet,
                'language' => $language
            ]
        );

        try {
            $this->apiValidator->checkForApiErrorResult($result);
        } catch (\Exception $e) {
            throw new \Exception(
                'Getting language xml for applet: (' . $applet . ') on language: (' . $language . ') was unsuccessful: '
                . $e->getMessage()
            );
        }

        return $result['data'];
    }
}