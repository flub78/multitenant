# Development


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

  
# Specific workflow
Most ot features will imply the following steps

* Creation of a model in a migration class
* Migration and creation of unit test for the model
* Creation of a Controller
* Creation of a Feature test for the controller
* Creation of an End-to-End test

## Creation of the model

    php artisan make:model --all CalendarEvent
    
    php artisan make:model --all Tenants\Configuration
    
    ()
    
    php artisan make:factory Tenants\CalendarEventFactory -m Tenants\CalendarEvent
    
it creates

    app\Http\Controllers\CalendarEventController.php
    app\Models\CalendarEvent.php
    database\factories\CalendarEventFactory.php
    database\migrations\2021_xx_yy_165312_create_celendar_events_table.php
    database\seeders\CalendarEventSeeder.php

In case of tenant feature some files need to be moved to the tenant directory.

Edit and adapt all the created files.
    
What is missing:

* the Request for validation in app\Http\Requests
* the views
* the tests, Model unit test and Feature controller test


### The migration

It can use any of the column types defined [here](https://laravel.com/docs/8.x/migrations#creating-columns)

Modifiers list can be found on the same page. The main ones are:
* ->comment('my comment')
* ->default($value)
* ->nullable($value = true)

to migrate a tenant database

see [Databases with tenants](databases_with_tenants.md)

### Update the routes

Either in routes/tenant.php or routes/web.php

### Give access to the controller in the GUI

Either in the navbar or other navigation mean.

### Create form

* Create a view
* Create a Request

    php artisan make:request Tenants\CalendarEventRequest

# How to

Some examples of workflow

## How to rename a filed in a database (refactoring)

### update the migration
### Migrate the database

migrate the tenant database

Note that the connection does not matter.
    php artisan tenants:migrate-fresh
    php artisan tenants:migrate-fresh --tenants=test
    php artisan tenants:migrate-fresh --tenants=abbeville
    
    php artisan tenants:seed --tenants=abbeville --class="Database\Seeders\RoleSeeder"
    
to migrate and seed
     

Several options: 
1. make the refactoring and run the tests, then fix the tests
1. just change the model, then run the test and fix them

### Example renaming category into description in calendar_events
* Change the migration
* migrate the database
* run the tests
* search fo the previous column name and replace it
* Including texts in language files