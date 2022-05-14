# Development Workflow


## General workflow

* Defines a feature using
  * As a user
  * I want to 
  * So ..
  
* Define validation scenarios
  * Given ..
  * When ..
  * Then ..
  
* Implement the validation scenarios that should not pass
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

I describe the workflow that I consider the most convenient in which the tables are created first in phpmyadmin and then the migration is generated from the table schema. If you are more comfortable writing migrations in PHP.

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

Delete the table from the tenanttest database.

    php artisan tenants:migrate --tenants=test
    
and check that the table is identical to the original version.

Be sure that the admin test user is registered in the test tenant database. Then regenerate
the test database.

    php artisan --tenant=test backup:create
    php artisan --tenant=test backup:test_install

Run all the tests for non regression.

## Creation of everything at once

For a full resource generated everything at once

    set table=motds
    php artisan mustache:generate --verbose --install %table% all

     
[If you prefer to generate files on by one](./code_generation_progress.md)

And run the test

    php vendor/phpunit/phpunit/phpunit  tests/Unit/Tenants/ProfileModelTest.php
    
There is currently no support in the code generator to generate the relationship methods in the model (hasOne, belongsTo, HasMany). They must be added manually.

Do not forget to complete the factory with error cases if you want tests on error cases.

## Creation a the controller and the views

Create the controller, a request, the views and a test for the controller. Also create an set of English strings for the views and a translation in your favorite language.

    php artisan mustache:generate --install %table% controller       
    php artisan mustache:generate --install %table% request         
    php artisan mustache:generate --install %table% test_controller 

    php artisan mustache:generate --install %table% index
    php artisan mustache:generate --install %table% create 
    php artisan mustache:generate --install %table% edit 
    php artisan mustache:generate --install %table% english 


    php artisan mustache:translate --install profile 

Declare a route for the controller into routes/tenant.php
    
    Route::resource('profile', App\Http\Controllers\Tenants\ProfileController::class)->middleware('auth');
    
At this point, the resource should be available through the controller:

    http://abbeville.tenants.com/profile
    
Test it manually and run the feature test.

    php vendor/phpunit/phpunit/phpunit tests/Feature/Tenants/ProfileControllerTest.php
    
## Optional creation of an API

    php artisan mustache:generate --install %table% api                  
    php artisan mustache:generate --install %table% test_api        

Declare a route for the API controller into routes/tenant.php

    Route::resource('api/profile', App\Http\Controllers\Api\profileController::class, ['as' => 'api'])
        ->middleware(['auth:sanctum', 'ability:check-status,api-access']);

The API can be manually tested with Postman (I do not know how to send the Sanctum token with a Web browser).
            
Then run the test

    php vendor/phpunit/phpunit/phpunit tests/Feature/Api/ProfileControllerTest.php
    
## End to End dusk test

    php artisan mustache:generate --install %table% test_dusk       
    
Note as chrome may be updated on the development platform, desynchronization of chrome and the chrome webdriver may be frequent:

    Facebook\WebDriver\Exception\SessionNotCreatedException: session not created: This version of ChromeDriver only supports Chrome version 98
    Current browser version is 101.0.4951.54 with binary path C:\Program Files\Google\Chrome\Application\chrome.exe
    
In this case the solution is to update the chrome web driver.

    php artisan dusk:chrome-driver 101
    
Once the driver is up to date you can run the test.

    php artisan dusk --colors=always --env=.env.dusk.tenants --browse tests/Browser/Tenants/ProfileTest.php
    
    
        