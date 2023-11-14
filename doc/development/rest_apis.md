# Writing REST APIs

One of the goal of this framework is facilitate the development of REST APIs. Most resources should be accessible through a REST API. 

## Example accessing the calendar events through a REST API

The command

    php artisan make:controller Api\CalendarEventController --resource --api
    
generates the file 

    \app\Http\Controllers\Api\CalendarEventController.php

## Simplified Workflow to create an API

The simplest way to create an API in this project is to use the code generator. See other documentation pages to create the database table and migrate it. 


    php artisan mustache:generate --install roles api 
    
Setup the route in the tenant.php file.

## Examples


http://abbeville.tenants.com/api/role
    
On success it returns a json answer

```
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
```

Another API:

http://abbeville.tenants.com/api/calendar_event

```
{"current_page":1,
    "data":[
        {"id":1,
        "title":"Dentiste",
        "description":null,
        "allDay":0,
        "start":"2023-07-11 12:43:00",
        "end":null,
        "backgroundColor":"#2bff00",
        "borderColor":null,
        "textColor":"#f90101","created_at":"2023-07-11T10:43:51.000000Z","updated_at":"2023-07-11T10:43:51.000000Z"},
        
        {"id":2,
        "title":"Default",
        "description":null,
        "allDay":0,
        "start":"2023-07-11 12:44:00",
        "end":null,
        "backgroundColor":"#00ffff",
        "borderColor":null,
        "textColor":"#808080","created_at":"2023-07-11T10:44:15.000000Z","updated_at":"2023-07-11T10:44:15.000000Z"},
        
        {"id":3,
        "title":"title",
        "description":null,
        "allDay":0,
        "start":"2023-07-11 12:45:00",
        "end":null,
        "backgroundColor":"#00ffff",
        "borderColor":null,"textColor":"#000000","created_at":"2023-07-11T10:45:49.000000Z","updated_at":"2023-07-11T10:45:49.000000Z"}],"first_page_url":"http:\/\/abbeville.tenants.com\/api\/calendar_event?page=1","from":1,"last_page":1,"last_page_url":"http:\/\/abbeville.tenants.com\/api\/calendar_event?page=1","links":[{"url":null,"label":"&laquo; Pr\u00e9c\u00e9dant","active":false},{"url":"http:\/\/abbeville.tenants.com\/api\/calendar_event?page=1","label":"1","active":true},{"url":null,"label":"&raquo; Suivante","active":false}],"next_page_url":null,"path":"http:\/\/abbeville.tenants.com\/api\/calendar_event","per_page":1000000,"prev_page_url":null,"to":3,"total":3}
```

## Authentication

### Sanctum
        
Official Laravel package. Sanctum is a simple package you may use to issue API tokens to your users without the complication of OAuth.

https://laravel.com/docs/10.x/sanctum

#### API Tokens

API tokens are currently generated from the /user/token route. Token are shown in the clear only then (the value stored in database is encrypted).

    token=4|rLKqYyyVoCmDMtLSgZsIFMUwLEkcIXkIo83HslKK All tokens: token=

I am only been able to test Sanctum protected API with Postman.
Use Authorization.Type = Bearer Token and past the value returned by the user/token controller.

When the authorization is declined the request is currently redirected to the home page. To be changed to return an access denied error.


#### Sanctum Installation
            
    composer require laravel/sanctum
    php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
    php artisan migrate 
        
## Error management

https://josipmisko.com/posts/rest-api-error-handling

