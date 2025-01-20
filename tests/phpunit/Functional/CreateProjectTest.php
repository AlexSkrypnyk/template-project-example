<?php

declare(strict_types=1);

namespace AlexSkrypnyk\TemplateProjectExample\Tests\phpunit\Functional;

use AlexSkrypnyk\Customizer\Tests\Functional\CustomizerTestCase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;

/**
 * Test Customizer as a dependency during `composer create-project`.
 */
class CreateProjectTest extends CustomizerTestCase {

  #[RunInSeparateProcess]
  #[Group('install')]
  public function testInstall(): void {
    // Set answers for the customizer.
    static::customizerSetAnswers([
      'testorg/testpackage',
      'Test description',
      'MIT',
      self::TUI_ANSWER_NOTHING, // Accept the default.
    ]);

    // Run `composer create-project`.
    $this->runComposerCreateProject();

    // Assert the customizer output.
    $this->assertComposerCommandSuccessOutputContains('Greetings from the customizer for the "alexskrypnyk/template-project-example" project');
    $this->assertComposerCommandSuccessOutputContains('Name          testorg/testpackage');
    $this->assertComposerCommandSuccessOutputContains('Description   Test description');
    $this->assertComposerCommandSuccessOutputContains('License       MIT');
    $this->assertComposerCommandSuccessOutputContains('Project was customized');

    // Assert the project structure.
    $this->assertFixtureDirectoryEqualsSut('post_install');

    // Assert the composer.lock file is up-to-date.
    $this->assertComposerLockUpToDate();
  }

  #[RunInSeparateProcess]
  #[Group('no-install')]
  public function testNoInstall(): void {
    // Set answers for the customizer.
    static::customizerSetAnswers([
      'testorg/testpackage',
      'Test description',
      'MIT',
      self::TUI_ANSWER_NOTHING, // Accept the default.
    ]);

    // Run `composer create-project` without installing dependencies.
    $this->runComposerCreateProject(['--no-install' => TRUE]);

    // Assert the directory structure after
    // the `composer create-project --no-install` command.
    // Using numeric prefix to have a clear order of fixture directories.
    $this->assertFixtureDirectoryEqualsSut('1_before_install');

    // Run the `composer install` command.
    $this->tester->run(['command' => 'install']);

    // Assert the directory structure after the `composer install` command.
    $this->assertFixtureDirectoryEqualsSut('2_post_install');
    // Assert the lock file is up-to-date.
    $this->assertComposerLockUpToDate();

    // Run the `composer customize` command.
    $this->tester->run(['command' => 'customize']);

    // Assert the customizer output.
    $this->assertComposerCommandSuccessOutputContains('Greetings from the customizer for the "alexskrypnyk/template-project-example" project');
    $this->assertComposerCommandSuccessOutputContains('Name          testorg/testpackage');
    $this->assertComposerCommandSuccessOutputContains('Description   Test description');
    $this->assertComposerCommandSuccessOutputContains('License       MIT');
    $this->assertComposerCommandSuccessOutputContains('Project was customized');

    // Assert the project structure.
    $this->assertFixtureDirectoryEqualsSut('3_post_customize');

    // Assert the lock file is up-to-date.
    $this->assertComposerLockUpToDate();
  }

}
