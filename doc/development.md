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
    
What is missing:

* the Request for validation in app\Http\Requests
* the views
* the tests

Note as Calendar is a tenant feature it needs to be moved in the correct directory.
    