# Work in progress

This file is just a reminder on the latest actions I'm working on.

## Start live server

    cd git/multitenant
    source setenv.sh
    php artisan serve --host=tenants.com --port=8000

    APP_ENV=testing php artisan serve --host=tenants.com --port=8000

## Fix Feature tests

    cd git/multitenant
    source setenv.sh

    php vendor/phpunit/phpunit/phpunit tests/Unit/
        Time: 01:51.927, Memory: 50.50 MB
        OK (153 tests, 734 assertions)

    php vendor/phpunit/phpunit/phpunit tests/Feature/
        ERRORS!         4/12/2024
        Tests: 271, Assertions: 1259, Errors: 2, Failures: 15, Skipped: 4.
        +40 assertions, -10 Failures

