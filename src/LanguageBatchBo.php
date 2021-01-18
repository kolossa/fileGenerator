<?php

namespace Language;

/**
 * Business logic related to generating language files.
 */
class LanguageBatchBo
{
    private ILanguageFileGenerator $languageFileGenerator;
    private ILanguageXmlFileGenerator $languageXmlFileGenerator;
    private ILogger $logger;

    public function __construct()
    {
        $applications = Config::get('system.translated_applications');
        $systemPathRoot = Config::get('system.paths.root');
        $apiCall = new ApiCallAdapter();
        $apiValidator = new ApiValidator();
        $applets = [
            'memberapplet' => 'JSM2_MemberApplet',
        ];
        $this->logger = new MonologAdapter();

        $this->languageFileGenerator = new LanguageFileGenerator(
            $applications, $systemPathRoot, $apiCall, $apiValidator, $this->logger
        );
        $this->languageXmlFileGenerator = new LanguageXmlFileGenerator(
            $systemPathRoot,
            $apiCall,
            $apiValidator,
            $applets,
            $this->logger
        );
    }

    /**
     * Starts the language file generation.
     *
     * @return void
     */
    public function generateLanguageFiles(): void
    {
        try {
            $this->languageFileGenerator->generateLanguageFiles();
        }catch (\Exception $e){
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * Gets the language files for the applet and puts them into the cache.
     *
     * @return void
     *
     */
    public function generateAppletLanguageXmlFiles(): void
    {
        try {
            $this->languageXmlFileGenerator->generateAppletLanguageXmlFiles();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
