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

Sanctum allows each user of your application to generate multiple API tokens for their account. These tokens may be granted abilities / scopes which specify which actions the tokens are allowed to perform.

Tokens are used to authenticate the user. They are equivalent to the user id and password used for authentication in the web interface. It is quite logical to allow the user to manage his own tokens. Admin users may also revoke or delete user tokens, for example if the user has lost his phone.

API tokens are currently generated from the **/user/token**
route. 

Token are shown in the clear only then (the value stored in database is encrypted).

    token=4|rLKqYyyVoCmDMtLSgZsIFMUwLEkcIXkIo83HslKK All tokens: token=

Postman, Insomnia or curl can be used to test REST APIs.

```
    curl -X GET \
     -H "Authorization: Bearer 2|kS2ObVVngb7up55vBjcLkoBWTkAHOeBrBVPUFga4081b4928" \
     -H "Accept: application/json" \
     http://abbeville.tenants.com:8000/api/role
```

With Postman, use Authorization.Type = Bearer Token and past the value returned by the user/token controller.

When the authorization is declined, a json error message is returned.

    {"message":"Unauthenticated."}




#### Sanctum Installation
            
    composer require laravel/sanctum
    php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
    php artisan migrate 
        
## Error management

https://josipmisko.com/posts/rest-api-error-handling

To handle in Json the not found exception edit /app/Exceptions/Handler.php. It works for all HTML verbs, GET, DELETE, etc..

## Testing

### Manual testing

* All the items
http://abbeville.tenants.com/api/calendar_event

* One item		
http://abbeville.tenants.com/api/calendar_event/1

* A non existing item (returns 404)		
http://abbeville.tenants.com/api/calendar_event/9999

* The you can use Insomnia or Postman to access protected API entries.
			
### Automated testing

The unit test vasic creation, update and delete, access to non existing elements, pagination, sort and basic filtering.

```
php vendor/phpunit/phpunit/phpunit tests/Feature/Api/CalendarEventControllerTest.php

php vendor/phpunit/phpunit/phpunit tests/Feature/Api/RoleControllerTest.php
```

## Laravel and Json

https://www.akilischool.com/cours/laravel-creer-une-api-json
https://laravel.com/docs/10.x/eloquent-serialization

There is some automatic support for Json and RST API in JSON.

In the correct context the toJson method is automatically called and the query result is automatically translated.

    return $query->paginate (15);

a more explicit way

```
    $users = User::all();

    // On retourne les informations des utilisateurs en JSON
    return response()->json($users);
```

### API generation

The artisan command can create a controller and the option --api removes the edit and create methods.

    php artisan make:controller API/UserController --model=User --api

Or the code generator may be used.


