<?php

namespace Language;

use Language\api\ApiCallAdapter;
use Language\api\ApiValidator;
use Language\FileGenerator\ILanguageFileGenerator;
use Language\FileGenerator\LanguageFileGenerator;
use Language\FileGenerator\LanguageFileGeneratorDTO;
use Language\FileGenerator\LanguageXmlFileGenerator;
use Language\FileGenerator\LanguageXmlFileGeneratorDTO;
use Language\Logger\ILogger;
use Language\Logger\MonologAdapter;

/**
 * Business logic related to generating language files.
 */
class LanguageBatchBo
{
    private ILanguageFileGenerator $languageFileGenerator;
    private ILanguageFileGenerator $languageXmlFileGenerator;
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

        $languageFileGeneratorDTO = new LanguageFileGeneratorDTO(
            $applications,
            $systemPathRoot,
            $apiCall,
            $apiValidator,
            $this->logger
        );
        $this->languageFileGenerator = new LanguageFileGenerator($languageFileGeneratorDTO);

        $languageXmlFileGeneratorDTO = new LanguageXmlFileGeneratorDTO(
            $systemPathRoot,
            $apiCall,
            $apiValidator,
            $applets,
            $this->logger
        );
        $this->languageXmlFileGenerator = new LanguageXmlFileGenerator($languageXmlFileGeneratorDTO);
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
        } catch (\Exception $e) {
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
            $this->languageXmlFileGenerator->generateLanguageFiles();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
