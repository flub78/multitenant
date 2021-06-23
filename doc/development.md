# Development

Most ot features will imply the following steps

* Creation of a model in a migration class
* Migration and creation of unit test for the model
* Creation of a Controller
* Creation of a Feature test for the controller
* Creation of an End-to-End test

## Creation of the model

    php artisan make:model --all CalendarEvent
    
it creates

    app\Http\Controllers\CalendarEventController.php
    app\Models\CalendarEvent.php
    database\factories\CalendarEventFactory.php
    database\migrations\2021_xx_yy_165312_create_celendar_events_table.php
    database\seeders\CalendarEventSeeder.php

Note as Calendar is a tenant feature it needs to be moved in the correct directory.

Edit and adapt all the created files.
    
What is missing:

* the Request for validation in app\Http\Requests
* the views
* the tests

Create a unit test for the model



### The migration

It can use any of the column types defined [here](https://laravel.com/docs/8.x/migrations#creating-columns)

Modifiers list can be found on the same page. The main ones are:
* ->comment('my comment')
* ->default($value)
* ->nullable($value = true)

to migrate a tenant database

    php artisan tenants:migrate
    
or

    php artisan tenants:migrate-fresh
    
And to prepare the test database

    php artisan tenants:migrate-fresh --database=mysql_test


    