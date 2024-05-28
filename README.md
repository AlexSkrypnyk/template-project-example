# template-project-example [![Test PHP](https://github.com/AlexSkrypnyk/template-project-example/actions/workflows/test-php.yml/badge.svg)](https://github.com/AlexSkrypnyk/template-project-example/actions/workflows/test-php.yml)
Example of how [Customizer](https://github.com/AlexSkrypnyk/customizer) can be used in the template project

```bash
composer create-project alexskrypnyk/template-project-example my-project
```

Example questions and processing are defined in the [`customize.php`](customize.php) file.

Automated tests for the example questions and processing are defined in the [`tests/CreateProjectTest.php`](tests/CreateProjectTest.php) file.

---

Given template project [`composer.json`](composer.json) before customization:
```json
{
    "name": "alexskrypnyk/template-project-example",
    "description": "Example of how Customizer could be used in the project",
    "type": "project",
    "require": {
        "php": ">=8.2"
    },
    "require-dev": {
        "alexskrypnyk/customizer": "^0.3",
        "composer/composer": "^2.7",
        "phpunit/phpunit": "^11.1"
    },
    "minimum-stability": "stable",
    "prefer-stable": true,
    "autoload-dev": {
        "psr-4": {
            "AlexSkrypnyk\\Customizer\\Tests\\": "vendor/alexskrypnyk/customizer/tests/phpunit",
            "AlexSkrypnyk\\TemplateProjectExample\\Tests\\": "tests"
        }
    },
    "config": {
        "allow-plugins": {
            "alexskrypnyk/customizer": true
        }
    }
}
```

and a `README.md` file with the following content:

```markdown
Welcome to the alexskrypnyk/template-project-example example!

Some description on how to use the project.
```

answering the questions with

```
Name: acme/my-project
Description: My project
License: MIT
```

will result in the `composer.json`:

```json
{
    "name": "acme/my-project",
    "description": "My project",
    "type": "project",
    "require": {
        "php": ">=8.2"
    },   
    "minimum-stability": "dev",
    "prefer-stable": true    
}
```
and the `README.md` file:

```markdown
Welcome to the acme/my-project example!

Some description on how to use the project.
```
