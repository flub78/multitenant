# Testing

## Types of test

* Unit tests for models and simple classes.

* Feature tests both for central application and tenant application. Test coverage is measured using xdebug both for unit and feature tests.

* Dusk tests (browser controlled tests) for end to end testing

* Deployment tests, (todo) the application is automatically installed from scratch and tested

* Performance test (todo). Significant performance indicator should be automatically collected and it should be possible to compare the performance level of every build.

* Robustness tests. (todo) These test check the behavior of the system under heavy load and should provide a way to size the servers against the expected load.

## TDD Test Driven Development

Test driven development. It implies the tests to be developed before the code. 

A line of code should not be written without a pre-existing red test to make green.

## BDD Behavior Driven Development

BDD can be considered as an extension of TDD. Its Objective is to directly test the code at the specification level. First a requirement is written, then this requirement is directly transformed into a test.

The advantages are a high level of coverage and a limited level of dependency between the code and the tests. It should be possible to change the implementation without changing the tests.

It is well suited for systems with a lot of requirements when gathering of requirements is hard and they need to be discussed a lot with stakeholders. It help requirements discussion by providing a common language in natural syntax.

In BDD test scenarios are often written as :

    Feature: Making backup
        As an admin user
        I want to trigger backups
        So I have them later in case of need

        rule: non admin users cannot start backups
        rule: ...

    Scenario: An admin user makes a backup
        Given I am an admin
        and I am logged in
        and on the backup page
        When I request a new backup
        Then it should be stored in the tenant storage and visible in the backup list
        
Usually there are several scenarios per feature.

By definition it should be applied at the feature test level (not unit test). Laravel multitenancy tends to transform tests that should logically be unit tests into feature tests because tenant testing require some context to be established (container) and container are only created for feature tests. There is no need to be BDD for these unit tests in the feature directory.

Should I use a special framework like cumcumber or behat to implement BDD? The simplest implementation is just to create BDD scenarios as comments inside regular tests. It can make it a little more difficult to access for non technical persons (the source of truth is inside the code). But a simple script to extract them may be simpler to write and more flexible than deploying a BDD framework.

It is always the same story, is it possible to apply the spirit of something without tools to support it ?(like writing object oriented programs in non object oriented languages or using a garbage collector on language which have none). The answer is usually yes but it requires more discipline, so it is only applicable to teams who decide to enforce the discipline. Note that it is a better situation than using  tool to support something with a team who do not want to use it.

### Current decision:

It is recommended to describe tests using BDD syntax in feature test comments. We will see later if the hassle to install and understand a BDD framework worth the effort.

Second step could be to organize all test routines to call subroutines starting by

    given_, and_, when_ and then_
    
Even if it is easy to extract features specifications and test scenarios it does not give an as good visibility on what features are not yet implemented. However if the rule is to always have all the test passing, it does not matter.

### Sources and references

    https://fr.slideshare.net/marcusamoore/behavior-driven-development-and-laravel        
    https://code.tutsplus.com/fr/tutorials/laravel-bdd-and-you-lets-get-started--cms-22155

## Multi-tenant testing

    https://tenancyforlaravel.com/docs/v3/testing
    
### phpunit fo central application

To test your central app, just write normal Laravel tests.

### phpunit for enants

Inherit from TenantTest to create a tenant context

Note: If you're using multi-database tenancy & the automatic mode, it's not possible to use :memory: SQLite databases or the RefreshDatabase trait due to the switching of default database.


#### On Windows

Test tenants domains must be declared in C:\Windows\System32\drivers\etc\hosts.

    127.0.0.1   test.tenants.com
    
Unfortunately, it is not trivial under windows to use wildcard for subdomains. So the tenant "test" and the subdomain "test.tenants.com" will be used for testing.

## Test databases

The mysql connection defined in config/database.php is used for manual testing.

The mysql_test connection is used for automated tests.

phpunit.xml

   <env name="DB_CONNECTION" value="mysql_test"/>
   
TestCase loads a specific environment for testing:

    $app->loadEnvironmentFrom('.env.testing');
    
# Browser controlled End to End testing

Laravel Dusk provides an easy-to-use browser automation and testing API. It is similar to Selenium.

Most Dusk tests restore a well known database before running. It implies that Dusk tests have access to the filesystem where the test backups are stored.

For tests on deployed servers another mechanism will be required. Maybe a backup restoration from an uploaded file.

## Installation

    composer require --dev laravel/dusk
    php artisan dusk:install
    
       