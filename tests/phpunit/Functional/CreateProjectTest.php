<?php

declare(strict_types=1);

namespace AlexSkrypnyk\TemplateProjectExample\Tests\phpunit\Functional;

use AlexSkrypnyk\Customizer\CustomizeCommand;
use AlexSkrypnyk\Customizer\Tests\Functional\CustomizerTestCase;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;

/**
 * Test Customizer as a dependency during `composer create-project`.
 */
class CreateProjectTest extends CustomizerTestCase {

  #[RunInSeparateProcess]
  public function testInstall(): void {
    $this->customizerSetAnswers([
      'testorg/testpackage',
      'Test description',
      'MIT',
      self::TUI_ANSWER_NOTHING,
    ]);
    $this->composerCreateProject();

    $this->assertComposerCommandSuccessOutputContains('Welcome to the "alexskrypnyk/template-project-example" project customizer');
    $this->assertComposerCommandSuccessOutputContains('Project was customized');

    $this->assertFileExists('composer.json');
    $this->assertFileExists('composer.lock');
    $this->assertDirectoryExists('vendor');
    // Plugin will only clean up after itself if there were questions.
    $this->assertDirectoryDoesNotExist('vendor/alexskrypnyk/customizer');

    $json_sut = CustomizeCommand::readComposerJson($this->dirs->sut . DIRECTORY_SEPARATOR . 'composer.json');
    $this->assertEquals($json_sut['name'], 'testorg/testpackage');
    $this->assertEquals($json_sut['description'], 'Test description');
    $this->assertEquals($json_sut['license'], 'MIT');
    $this->assertFalse(isset($json_sut['require-dev']['alexskrypnyk/customizer']));
    $this->assertFalse(isset($json_sut['require-dev']['phpunit/phpunit']));

    $this->assertArrayNotHasKey('config', $json_sut);
    $this->assertFileDoesNotExist($this->customizerFile);
    $this->assertDirectoryDoesNotExist('tests');

    $this->assertComposerLockUpToDate();
  }

}
