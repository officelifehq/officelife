workflow "phpunit / phpinsights / php-cs-fixer" {
  on = "push"
  resolves = [
    "phpunit",
    "phpinsights",
    "auto-commit-php-cs-fixer",
  ]
}

# Install composer dependencies
action "composer install" {
  uses = "MilesChou/composer-action@master"
  args = "install -q --no-ansi --no-interaction --no-scripts --no-suggest --no-progress --prefer-dist"
}

# Run phpunit testsuite
action "phpunit" {
  needs = ["composer install"]
  uses = "./actions/run-phpunit/"
  args = "tests/"
}

# Run phpinsights
action "phpinsights" {
  needs = ["composer install"]
  uses = "stefanzweifel/laravel-phpinsights-action@v1.0.0"
  args = "-v --min-quality=80 --min-complexity=80 --min-architecture=80 --min-style=80"
}

# Run php-cs-fixer
action "php-cs-fixer" {
  uses = "docker://oskarstark/php-cs-fixer-ga"
}

action "auto-commit-php-cs-fixer" {
  needs = ["php-cs-fixer"]
  uses = "stefanzweifel/git-auto-commit-action@v1.0.0"
  secrets = ["GITHUB_TOKEN"]
  env = {
    COMMIT_MESSAGE = "Apply php-cs-fixer changes"
    COMMIT_AUTHOR_EMAIL  = "jon.doe@example.com"
    COMMIT_AUTHOR_NAME = "Jon Doe"
  }
}
