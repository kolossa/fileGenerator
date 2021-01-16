<?php


namespace Language;


class LanguageXmlFileGenerator implements ILanguageXmlFileGenerator
{
    protected string $systemPathRoot;
    protected IApiCall $apiCall;
    protected ApiValidator $apiValidator;

    /**
     * LanguageXmlFileGenerator constructor.
     * @param string $systemPathRoot
     * @param IApiCall $apiCall
     * @param ApiValidator $apiValidator
     */
    public function __construct(string $systemPathRoot, IApiCall $apiCall, ApiValidator $apiValidator)
    {
        $this->systemPathRoot = $systemPathRoot;
        $this->apiCall = $apiCall;
        $this->apiValidator = $apiValidator;
    }


    public function generateAppletLanguageXmlFiles(): void
    {
        // List of the applets [directory => applet_id].
        $applets = array(
            'memberapplet' => 'JSM2_MemberApplet',
        );

        echo "\nGetting applet language XMLs..\n";

        foreach ($applets as $appletDirectory => $appletLanguageId) {
            echo " Getting > $appletLanguageId ($appletDirectory) language xmls..\n";
            $languages = $this->getAppletLanguages($appletLanguageId);
            if (empty($languages)) {
                throw new \Exception('There is no available languages for the ' . $appletLanguageId . ' applet.');
            } else {
                echo ' - Available languages: ' . implode(', ', $languages) . "\n";
            }
            $path = $this->systemPathRoot . '/cache/flash';
            foreach ($languages as $language) {
                $xmlContent = $this->getAppletLanguageFile($appletLanguageId, $language);
                $xmlFile = $path . '/lang_' . $language . '.xml';
                if (strlen($xmlContent) == file_put_contents($xmlFile, $xmlContent)) {
                    echo " OK saving $xmlFile was successful.\n";
                } else {
                    throw new \Exception(
                        'Unable to save applet: (' . $appletLanguageId . ') language: (' . $language
                        . ') xml (' . $xmlFile . ')!'
                    );
                }
            }
            echo " < $appletLanguageId ($appletDirectory) language xml cached.\n";
        }

        echo "\nApplet language XMLs generated.\n";
    }

    /**
     * Gets the available languages for the given applet.
     *
     * @param string $applet The applet identifier.
     *
     * @return array   The list of the available applet languages.
     */
    protected function getAppletLanguages($applet)
    {
        $result = $this->apiCall->call(
            'system_api',
            'language_api',
            array(
                'system' => 'LanguageFiles',
                'action' => 'getAppletLanguages'
            ),
            array('applet' => $applet)
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
    protected function getAppletLanguageFile($applet, $language)
    {
        $result = $this->apiCall->call(
            'system_api',
            'language_api',
            array(
                'system' => 'LanguageFiles',
                'action' => 'getAppletLanguageFile'
            ),
            array(
                'applet' => $applet,
                'language' => $language
            )
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