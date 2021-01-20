<?php


namespace tests\integration;

use Language\api\ApiCallAdapter;
use Language\api\ApiCallDTO;
use Language\FileGenerator\LanguageXmlFileGenerator;
use Language\FileGenerator\LanguageXmlFileGeneratorDTO;
use PHPUnit\Framework\TestCase;
use Language\api\ApiValidator;

final class LanguageXmlFileGeneratorTest extends TestCase
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
        $apiValidator = new ApiValidator();
        $applets = [
            'memberapplet' => 'JSM2_MemberApplet',
        ];
        $logger = new FakeLogger();

        $languages = ['en'];
        $fileContent = 'test';
        $apiCall = $this->createMock(ApiCallAdapter::class);
        $apiCall->expects($this->exactly(2))
            ->method('call')
            ->willReturnOnConsecutiveCalls(
                new ApiCallDTO('OK', $languages, null),
                new ApiCallDTO('OK', $fileContent, null)
            );

        $languageXmlFileGeneratorDTO = new LanguageXmlFileGeneratorDTO(
            $this->systemPathRoot,
            $apiCall,
            $apiValidator,
            $applets,
            $logger
        );
        $languageXmlFileGenerator = new LanguageXmlFileGenerator($languageXmlFileGeneratorDTO);

        $languageXmlFileGenerator->generateLanguageFiles();

        $path = $this->systemPathRoot . DIRECTORY_SEPARATOR . '/cache/flash';
        $this->assertDirectoryExists($path);
        foreach ($applets as $appletDirectory => $appletLanguageId) {
            foreach ($languages as $language) {
                $file = $path . DIRECTORY_SEPARATOR . 'lang_' . $language . '.xml';
                $this->assertFileExists($file);
                $this->assertGreaterThan(0, filesize($file));
                $content = file_get_contents($file);
                $this->assertEquals($fileContent, $content);
            }
        }
    }

    protected function tearDown(): void
    {
        $cleaner = new DirCleanerTask();
        $cleaner->removeCache($this->systemPathRoot);
    }
}