<?php

declare(strict_types=1);

namespace AlexSkrypnyk\TemplateProjectExample\Tests;

use AlexSkrypnyk\Customizer\Tests\Functional\CustomizerTestCase;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;

/**
 * Test Customizer as a dependency during `composer create-project`.
 */
class CreateProjectTest extends CustomizerTestCase {

  #[RunInSeparateProcess]
  public function testPluginInstall(): void {
    $this->customizerSetAnswers([
      'testorg/testpackage',
      'Test description',
      'MIT',
      self::TUI_ANSWER_NOTHING,
    ]);
    $this->composerCreateProject();

    $this->assertComposerCommandSuccessOutputContains('Welcome to the alexskrypnyk/template-project-example project customizer');
    $this->assertComposerCommandSuccessOutputContains('Project was customized');

    $this->assertFileExists('composer.json');
    $this->assertFileExists('composer.lock');
    $this->assertDirectoryExists('vendor');
    // Plugin will only clean up after itself if there were questions.
    $this->assertDirectoryDoesNotExist('vendor/alexskrypnyk/customizer');

    $json = $this->composerJsonRead('composer.json');
    $this->assertEquals($json['name'], 'testorg/testpackage');
    $this->assertEquals($json['description'], 'Test description');
    $this->assertEquals($json['license'], 'MIT');

    $this->assertArrayNotHasKey('require-dev', $json);
    $this->assertArrayNotHasKey('config', $json);
    $this->assertFileDoesNotExist($this->customizerFile);
    $this->assertDirectoryDoesNotExist('tests');

    $this->assertComposerLockUpToDate();
  }

}
