# Writing APIs

One of the goal of this framework is to provide a REST API service. Most, if not all resources should be accessible through a REST API. API access to the central application is of lower priority than API access for tenants.

## Example accessing the calendar events through a REST API


    php artisan make:controller Api\CalendarEventController --resource --api
    
generates \app\Http\Controllers\Api\CalendarEventController.php

