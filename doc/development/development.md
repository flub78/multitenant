# Development Workflow


## General workflow for a new feature

Defines use cases using
  * As a user
  * I want to 
  * So ..
  
Define validation scenarios
  * Given ..
  * When ..
  * Then ..
  
Implement the validation scenarios that should not pass
  * Write the code
  * write unit tests for the code

  
# Resource CRUD workflow

For resources, the usual workflow is

* Creation of a migration
* Migrate
* Include the migration into the test database
* Model and unit tests
* Controller and feature tests
* Generate the REST API
* End-to-End tests

This is the workflow that I consider the most convenient in which the tables are created first in phpmyadmin and then the migration is generated from the table schema. If you are more comfortable writing migrations in PHP you can skip this step and create the migration with Artisan.

    php artisan make:migration Profiles
 
then edit and adapt the migration.


## Migration from the schema

### Create the table in tenanttest
      
![New table](images/new_table.PNG?raw=true "How to create a table")
    
#### Define the columns

Most of the time

    id
        - a bigint
        - unsigned attribut
        - primary
        - Auto incremented

    created_at
        - timestamp
        - default = current_timestamp()
        - comment: {"fillable":"no", "inTable":"no", "inForm":"no"}
    

    
    
![Table creation form](images/creation_form.PNG?raw=true "Creation form")

#### Check the indexes

![Indexes](images/indexes.PNG?raw=true "Indexes")
 
#### Create foreign key constraints

![Constraints](images/create_constraint.PNG?raw=true "Constraints")

####  And the result must be

![Alt text](images/phpmyadmin_table_structure.PNG?raw=true "Title")

### Generate the migration

    set table=motds    
    php artisan mustache:generate --install %table% migration

    
![WinMerge](images/WinMerge.PNG?raw=true "WinMerge")
    
### Migrate

Delete the table from the tenanttest database, then migrate.

    php artisan tenants:migrate --tenants=test
    
and check that the table is identical to the original version.

Be sure that the admin test user is registered in the test tenant database. Then regenerate
the test database.

    php artisan --tenant=test backup:create
    php artisan --tenant=test backup:test_install

## Creating all files related to the resource

    set table=motds
    php artisan mustache:generate --verbose --install %table% all

And follow the instructions displayed per the tool:

    ===============================================================================================================
    The resources files for motds have been generated

    Model

        app/Models/Tenants/Motd.php

        There is currently no support in the code generator to generate the relationship methods in the model
        (hasOne, belongsTo, HasMany). They must be added manually.

        Do not forget to complete the factory with error cases if you want tests on error cases.

    Localization Strings

        Review the generated language files:
            resources/lang/en/motd.php
            resources/lang/fr/motd.php

    Routes declaration

        The following routes should be added in routes/tenant.php

        Route::resource('motd', App\Http\Controllers\Tenants\MotdController::class)
            ->middleware('auth');

        Route::resource('api/motd', App\Http\Controllers\Api\MotdController::class, ['as' => 'api'])
            ->middleware(['auth:sanctum', 'ability:check-status,api-access']);

    Test

        The resource is available at http://abbeville.tenants.com/motd
        and the API at  http://abbeville.tenants.com/api/motd

        The API can be manually tested with Postman (I do not know how to send the Sanctum token with a Web browser).

    Run the tests and add them to the test scripts

        php vendor/phpunit/phpunit/phpunit  tests/Unit/Tenants/MotdModelTest.php
        php vendor/phpunit/phpunit/phpunit  tests/Feature/Tenants/MotdControllerTest.php
        php vendor/phpunit/phpunit/phpunit  tests/Feature/Api/MotdControllerTest.php
        php vendor/phpunit/phpunit/phpunit  tests/Browser/Tenants/MotdTest.php
    ===============================================================================================================     

[If you prefer to generate files on by one, see](./code_generation_progress.md)

    
## In case of errors with the End to End dusk test
    
Note as chrome may be updated on the development platform, desynchronization of chrome and the chrome webdriver may occur:

    Facebook\WebDriver\Exception\SessionNotCreatedException: session not created: This version of ChromeDriver only supports Chrome version 98
    Current browser version is 101.0.4951.54 with binary path C:\Program Files\Google\Chrome\Application\chrome.exe
    
In this case the solution is to update the chrome web driver.

    php artisan dusk:chrome-driver 101
    
Once the driver is up to date you can run the test.

    php artisan dusk --colors=always --env=.env.dusk.tenants --browse tests/Browser/Tenants/ProfileTest.php
    
    