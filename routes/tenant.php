<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
	
	Route::get('/', function () {
		return view('welcome');
	});
		
	Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
		
	Auth::routes();
	
	// admin routes
	Route::group(['middleware' => ['admin']], function () {
		Route::resource('users', App\Http\Controllers\UserController::class)->middleware('auth');
	});
	
});
