<?php

use Language\api\ApiValidator;
use Language\FileGenerator\LanguageFileGenerator;
use Language\FileGenerator\LanguageFileGeneratorDTO;
use PHPUnit\Framework\TestCase;
use tests\integration\DirCleanerTask;
use tests\integration\FakeLogger;
use tests\integration\FakeApiCall;

final class LanguageFileGeneratorTest extends TestCase
{
    private $systemPathRoot;

    protected function setUp(): void
    {
        $this->systemPathRoot = realpath(dirname(__FILE__));
    }

    /**
     * @test
     */
    public function generateFiles(): void
    {
        $applications = ['portal_test' => ['en', 'hu']];

        $apiCall = new FakeApiCall();
        $apiValidator = new ApiValidator();
        $logger = new FakeLogger();

        $dto = new LanguageFileGeneratorDTO($applications, $this->systemPathRoot, $apiCall, $apiValidator, $logger);
        $languageFileGenerator = new LanguageFileGenerator($dto);

        $languageFileGenerator->generateLanguageFiles();

        $this->assertDirectoryExists($this->systemPathRoot . DIRECTORY_SEPARATOR . 'cache');
        foreach ($applications as $application => $languages) {
            $applicationDir = $this->systemPathRoot . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . $application;
            $this->assertDirectoryExists($applicationDir);

            foreach ($languages as $language) {
                $file = $applicationDir . DIRECTORY_SEPARATOR . $language . '.php';
                $this->assertFileExists($file);
                $this->assertGreaterThan(0, filesize($file));
                $content = file_get_contents($file);
                $this->assertEquals('test', $content);
            }
        }
    }

    protected function tearDown(): void
    {
        $cleaner = new DirCleanerTask();
        $cleaner->removeCache($this->systemPathRoot);
    }
}