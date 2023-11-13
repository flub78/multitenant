# Middlewares

As the application is multitenant, most of the routes and associated middleware are described in the the file routes/tenant.php

## Authentication middleware

Used to restrict WEB pages to authenticated users.

    Route::get('/info', [App\Http\Controllers\TestController::class, 'info'])->name('info')->middleware('auth');

## API middleware

Use the sanctum middleware to control access to the API.

    Route::resource('api/profile', App\Http\Controllers\Api\ProfileController::class, ['as' => 'api'])
			->middleware(['auth:sanctum', 'ability:check-status,api-access']);

### Non secured
http://abbeville.tenants.com/api/calendar_event

http://abbeville.tenants.com/api/code_gen_type

# Secured

http://abbeville.tenants.com/api/role


