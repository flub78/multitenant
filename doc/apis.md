# Writing APIs

One of the goal of this framework is to provide a REST API service. Most, if not all resources should be accessible through a REST API. API access to the central application is of lower priority than API access for tenants.

## Example accessing the calendar events through a REST API


    php artisan make:controller Api\CalendarEventController --resource --api
    
generates \app\Http\Controllers\Api\CalendarEventController.php

## Simplified Workflow to create an API

See other documentation pages to create the database table and migrate it


    php artisan mustache:generate --install roles api 
    
Setup the route in the tenant.php file.

    http://abbeville.tenants.com/api/role
    
On success it returns a json answer

{"current_page":1,
    "data":[
        {
            "id":1,
            "name":"writer",
            "description":null,
            "created_at":"2022-01-26T08:31:57.000000Z",
            "updated_at":"2022-01-26T08:31:57.000000Z"},
        {
            "id":2,
            "name":"observer",
            "description":null,
            "created_at":"2022-01-26T08:32:05.000000Z",
            "updated_at":"2022-01-26T08:32:05.000000Z"
        }
    ],
    "first_page_url":"http:\/\/abbeville.tenants.com\/api\/role?page=1",
    "from":1,
    "last_page":1,
    "last_page_url":"http:\/\/abbeville.tenants.com\/api\/role?page=1",
    "links":[{"url":null,"label":"&laquo; Pr\u00e9c\u00e9dant",
    "active":false},
    {"url":"http:\/\/abbeville.tenants.com\/api\/role?page=1",
    "label":"1",
    "active":true},
    {"url":null,
    "label":"&raquo; Suivante","active":false}],"next_page_url":null,"path":"http:\/\/abbeville.tenants.com\/api\/role","per_page":1000000,"prev_page_url":null,"to":2,"total":2}

