# Testing

## Types of test

* Unit tests for models and simple classes.

* Feature tests both for central application and tenant application. Test coverage is measured using xdebug both for unit and feature tests.

* Dusk tests (browser controlled tests) for end to end testing

* Deployment tests, (todo) the application is automatically installed from scratch and tested

* Performance test (todo). Significant performance indicator should be automatically collected and it should be possible to compare the performance level of every build.

* Robustness tests. (todo) These test check the behavior of the system under heavy load and should provide a way to size the servers against the expected load.

## Multi-tenant testing

### Central application

Test as a regular Laravel Application

### Tenants

* Inherit from TenantTest to create a tenant context

* Do not use RefreshDatabase

There is an issue to test tenant with subdomains. On windows, I used to declare test tenants in the
etc/hosts file. It is not compatible with dynamic creation of tenant for automated testing.

So either I use always the same static tenant for testing or I need a way to dynamically declare the subdomain. 

## Test databases

The mysql connection defined in config/database.php is used for manual testing.

The mysql_test connection is used for automated tests.

phpunit.xml

   <env name="DB_CONNECTION" value="mysql_test"/>
   
TestCase loads a specific environment for testing:

    $app->loadEnvironmentFrom('.env.testing');
    
# Browser controlled End to End testing

Laravel Dusk provides an easy-to-use browser automation and testing API. It is similar to Selenium.

## Installation

    composer require --dev laravel/dusk
    php artisan dusk:install
    
       