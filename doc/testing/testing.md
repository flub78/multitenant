# Testing

## Types of test

### Unit tests

Unit tests for models and simple classes. They are stored in tests/Unit. They involve only one class and tests its methods. Test jobs measure the line coverage.

### Feature tests

Feature tests for central application and tenant applications. Test coverage is measured using xdebug. Feature tests require the collaboration of several classes and they are executed in an environment closed to real Laravel execution. Laravel instance, database and tenant environments are available in this context. They are slower than Unit tests. 

They use phpunit integrated in the Laravel Framework. They are stored in tests/Feature.

To follow redirection use:

        $response = $this->followingRedirects()->get ( '/login' );

### Integration end to end tests

They are browser controlled end to end dusk tests.

They have access to the database and can check the database status after action. They are stored in tests/Browser/Central and tests/Browser/Tenants.

### Installation or deployment tests. 

They are browser controlled end to end dusk tests.

They are deployed on an external server. The test environment has the same access than a regular user (no access to the database). Some of the tests are installation or deployment tests, they check that the system can be installed or deployed smoothly. 
They are stored in tests/Browser/Deployment.

### Live system tests. 

They are browser controlled end to end dusk tests.

They can be executed on a live system that contains production data. The production data are not modified by the tests and the data created by the tests are deleted when the tests are over. They are full black box tests. There only accepted impact on the production system is the impact on performance as they can generate more load on the system.

Avoid duplication. Integration tests are not fully black box because they can access the database directly to check the result of a test action. It makes the tests simpler but most of this information can also be extracted from the API REST interfaces and from the GUI. For example extracting the number of element in a table can usually be extracted from the GUI.

It is appealing to develop a test library to extract these information and move most of the testing from integration to live system tests. The pre-production tests test exactly the same thing, they are just a little more fragile and complex because they rely on additional library layers to extract information. They may also be slower.

It is relatively obvious that end to end integration test can be considered as a development step of pre-deployment tests. Should they remain as entities of their own ? or should they be transformed into fully black box pre-production tests ?

If the speed difference is low, it makes sense to avoid duplication. If end to end integration tests are much faster than fully black box tests, it makes sense to keep them as preliminary tests. In this case it could also be convenient to develop a test layer to access the database and implement it either with direct database accesses or through more complex GUI or API interactions. In this case it would be possible to run the exact same tests scenarios on the two contexts.

### Performance test (todo). 

Significant performance indicator should be automatically collected and it should be possible to compare the performance level of every build.

### Robustness tests. (todo)

These test check the behavior of the system under heavy load and should provide a way to size the servers against the expected load.

A discussion on how to chose between unit test and BDD (given-when-then) tests. https://specflow.org/challenges/bdd-vs-unit-tests/

## Testing philosophies

### Considerations on Unit testing

The goal of unit testing is to test a simple class without implying other classes. It is usually relatively easy for simple classes that render a service of their own and do not call others classes.

Feature tests are used when several classes are implicated.

Here is a interesting discussion on the test of private methods. 
    https://stackoverflow.com/questions/249664/best-practices-to-test-protected-methods-with-phpunit
    
IMHO :

* Every code should be unit tested, even private methods. I understand that it breaks the black box approach for testing, but for me black box testing is only an absolute requirement for end to end testing. Simply having the knowledge of the classes used by an implementation breaks the black box approach. Unit testing is white box even if you try to not have to much coupling between tests and implementation.

In other words BDD, and end to end tests are black box and loosely coupled with the implementation, unit tests are white box and more heavily coupled with the implementation. It is not chocking to have to rewrite them if you rewrite the code.

Note also that efforts to keep a high percentage of lines covered by unit testing is also a white box approach.

The suggestion to only test private method indirectly looks biased to me. If you have to design your input test to cover the internal private method you are already doing implementation dependent testing. In others words, if you do that, you already have a detailed knowledge of the implementation.

Note also that the importance of a strict encapsulation, which makes all the methods which are not strictly part of the API private, depends on the audience of the software. It is critical for a large open source project used by thousands of developers. It is less important of a private project or something used by a small team. 

I am aware of the relative weakness of the previous sentences. A lot of software bugs have happens exactly because the developers did not expect their software to be used in a wider context (year 2000 bug, Ariane 5, etc). So breaking encapsulation of private methods by making them public just for testing may be a bad idea.

Conclusion: it may be a good idea to spend some time to experiment on the reflexive methods giving assess to private methods for testing. It may also be a good idea to think that it is a low priority task.

And last remarks, if you can use reflexivity to get access to private methods, it means that the private, protected, public classification is more a convention than a security mechanism. In this case the python approach of making things private by convention may be good enough and I should not care too much about keeping public only for testing some methods that should logically be private.

### TDD Test Driven Development

Test driven development. It implies the tests to be developed before the code. 

A line of code should not be written without a pre-existing red test to make green.

### BDD Behavior Driven Development

BDD can be considered as an extension of TDD. Its Objective is to directly test the code at the specification level. First a requirement is written, then this requirement is directly transformed into a test.

The advantages are a high level of coverage and a limited level of dependency between the code and the tests. It should be possible to change the implementation without changing the tests.

It is well suited for systems with a lot of requirements when gathering of requirements is hard and they need to be discussed a lot with stakeholders. It help requirements discussion by providing a common language in natural syntax.

In BDD test scenarios are often written as :

    /**
     * Feature: Making backup
     *      As an admin user
     *      I want to trigger backups
     *      So I have them later in case of need
     *
     *      rule: non admin users cannot start backups
     *      rule: ...
     */

    /**
     * Scenario: An admin user makes a backup
     *      Given I am an admin
     *      and I am logged in
     *      and on the backup page
     *      When I request a new backup
     *      Then it should be stored in the tenant storage and visible in the backup list
     */
        
Usually there are several scenarios per feature.

By definition it should be applied at the feature test level (not unit test). Laravel multitenancy tends to transform tests that should logically be unit tests into feature tests because tenant testing require some context to be established (container) and containers are only created for feature tests. There is no need to be BDD for these unit tests in the feature directory.

Should I use a special framework like cucumber or behat to implement BDD? The simplest implementation is just to create BDD scenarios as comments inside regular tests. It can make it a little more difficult to access test descriptions for non technical persons (the source of truth is inside the code). But a simple script to extract them may be simpler to write and more flexible than deploying a BDD framework.

The drawback of this approach is that it requires more discipline.

Note that by definition BDD should be defined at the feature or behavior level and stay independent of the implementation. I have seen a lot of examples on the net which violate this principle where tests are described as when I visit this url and fill this field with this value then I see this string on the screen. Of course it is too much details and totally dependent on the implementation. We should always keep in mind that the tests should stay valid with a totally different implementation.

The usual approach is to develop test drivers which translate the high level feature description into implementation dependent operations. The BDD spirit is preserved if and only a change in implementation keep the test definition valid and only require modifications inside the test drivers.

Advantages of BDD framework:

* They provides an easy access to test descriptions to stakeholders.
* It is easier to organize tests into structure features and they are easier to find. In others approaches the test of a feature can be split between several tests.
* They are quite systematic and every feature has a test

Drawbacks pf a BDD framework

* I am not sure that they interface nicely with phpunit, especially in the multi tenant context which already imposes several limitations on Laravel tests.
* There is an additional cost to develop the test drivers
* In case of implementation change the cost to adapt the test drivers can be significant but no more than the cost of adapting implementation dependent tests 


#### Current decision:

A best effort will be done to describe tests using BDD syntax in feature test comments. We will see later if the hassle to install and understand a BDD framework worth the effort.

A second step could be to organize all test routines to call subroutines starting by

    given_, and_, when_ and then_
    
Even if it is easy to extract features specifications and test scenarios it does not give an as good visibility on what features are not yet implemented. However if the rule is to always have all the test passing, it does not matter.

### Sources and references

    https://fr.slideshare.net/marcusamoore/behavior-driven-development-and-laravel        
    https://code.tutsplus.com/fr/tutorials/laravel-bdd-and-you-lets-get-started--cms-22155

## Adaptation of testing to multitenancy

    https://tenancyforlaravel.com/docs/v3/testing
    
### phpunit for central application

To test your central app, just write normal Laravel tests.

### phpunit for tenants

Inherit from TenantTest to create a tenant context

Note: If you're using multi-database tenancy & the automatic mode, it's not possible to use :memory: SQLite databases or the RefreshDatabase trait due to the switching of default database.


#### On Windows

Test tenants domains must be declared in C:\Windows\System32\drivers\etc\hosts.

    127.0.0.1   test.tenants.com
    
Unfortunately, it is not trivial under windows to use wildcard for subdomains. So the tenant "test" and the subdomain "test.tenants.com" will be used for testing.

### Test databases

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
    
       