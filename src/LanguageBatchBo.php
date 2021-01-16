<?php

namespace Language;

/**
 * Business logic related to generating language files.
 */
class LanguageBatchBo
{
    protected ILanguageFileGenerator $languageFileGenerator;
    protected ILanguageXmlFileGenerator $languageXmlFileGenerator;

    public function __construct()
    {
        $applications = Config::get('system.translated_applications');
        $systemPathRoot = Config::get('system.paths.root');
        $apiCall = new ApiCallAdapter();
        $apiValidator=new ApiValidator();

        $this->languageFileGenerator = new LanguageFileGenerator($applications, $systemPathRoot, $apiCall, $apiValidator);
        $this->languageXmlFileGenerator = new LanguageXmlFileGenerator($systemPathRoot, $apiCall, $apiValidator);
    }

    /**
     * Starts the language file generation.
     *
     * @return void
     */
    public function generateLanguageFiles(): void
    {
        $this->languageFileGenerator->generateLanguageFiles();
    }

    /**
     * Gets the language files for the applet and puts them into the cache.
     *
     * @return void
     * @throws Exception   If there was an error.
     *
     */
    public function generateAppletLanguageXmlFiles(): void
    {
        $this->languageXmlFileGenerator->generateAppletLanguageXmlFiles();
    }
}
