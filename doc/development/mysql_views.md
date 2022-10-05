# MySql Views

MySQL are an elegant way to store and maintain complex selects.

Maintenance of the application could be easier if all the complex selects are stored in the database instead of being scattered across the code.

Together with the code generator they are an efficient method to generate an application. Once the basic database schema is defined and the code to handle it is generated, it is easy to store the application selects as MySql views. The code generator aware of the views and can generate the matching code.

## Remarks

If this approach is powerful it generates one controller, one model and one index view for every MySql view. For filtering fields in views it is likely more efficient to implement the filtering mechanism in the controllers. 

## How to create a MySql view with a Laravel migration

[How to use MySQL views in Laravel](https://www.nicesnippets.com/blog/how-to-use-mysql-view-in-laravel-8)

### Generate a migration

	php artisan make:migration MotdToday
	
		Created Migration: 2022_10_05_092409_motd_today
		
Move the migration in the correct dirrectory

### Create the view with phpmysadmin

Generate a SQL query

	SELECT * FROM `motds` WHERE `publication_date` >= CURDATE() and (CURDATE() < `end_date` or `end_date` IS NULL);

From the phpmyadmin result page choose create a view


### Update the migration and migrate

	php artisan tenants:migrate-fresh --tenants=test
	
### generate code for the new view
	
	php artisan mustache:generate --install motd_todays all

processing controller
processing model
processing request
processing index
processing create
processing edit
processing show
processing english
processing api
processing factory
processing test_model
processing test_controller
processing test_dusk
processing test_api

French translation: resources/lang/fr/motd_today.php
===============================================================================================================
The resources files for motd_todays have been generated

Model

    app/Models/Tenants/MotdToday.php

    There is currently no support in the code generator to generate the relationship methods in the model
    (hasOne, belongsTo, HasMany). They must be added manually.

    Do not forget to complete the factory with error cases if you want tests on error cases.

Localization Strings

    Review the generated language files:
        resources/lang/en/motd_today.php
        resources/lang/fr/motd_today.php

Routes declaration

    The following routes should be added in routes/tenant.php

    Route::resource('motd_today', App\Http\Controllers\Tenants\MotdTodayController::class)
        ->middleware('auth');

    Route::resource('api/motd_today', App\Http\Controllers\Api\MotdTodayController::class, ['as' => 'api'])
        ->middleware(['auth:sanctum', 'ability:check-status,api-access']);

Test

    The resource is available at http://abbeville.tenants.com/motd_today
    and the API at  http://abbeville.tenants.com/api/motd_today

    The API can be manually tested with Postman (I do not know how to send the Sanctum token with a Web browser).

Run the tests and add them to the test scripts

    php vendor/phpunit/phpunit/phpunit  tests/Unit/Tenants/MotdTodayModelTest.php
    php vendor/phpunit/phpunit/phpunit  tests/Feature/Tenants/MotdTodayControllerTest.php
    php vendor/phpunit/phpunit/phpunit  tests/Feature/Api/MotdTodayControllerTest.php
    php vendor/phpunit/phpunit/phpunit  tests/Browser/Tenants/MotdTodayTest.php
===============================================================================================================
	
## Views backup and restore

Views are backed up and restored with the regular mechanism. Inside the backup file, the instructions to regenerate the views are inside comments. It is a mysql feature as views are a specific to MySql and are just ignored if restored on another brand of database.

Note that json comments are also backed up and restored and it is a good thing for code generation.
