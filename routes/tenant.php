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
	
	/*
	 * Warning: routes are parsed in order of declaration and resource matches all the sub urls
	 */
	Route::get('calendar/fullcalendar', [App\Http\Controllers\Tenants\CalendarEventController::class, 'fullcalendar'])
		->name('calendar.fullcalendar')->middleware('auth');
	
	Route::get('/calendar/json', [App\Http\Controllers\Tenants\CalendarEventController::class, 'json'])
		->name('calendar.json')->middleware('auth');
	
	Route::resource('calendar', App\Http\Controllers\Tenants\CalendarEventController::class)->middleware('auth');

	
	Auth::routes();
	
	// admin routes
	Route::group(['middleware' => ['admin']], function () {
		Route::resource('users', App\Http\Controllers\UserController::class)->middleware('auth');
		
		// Route::get('configuration/{key}');
		
		Route::resource('configuration', App\Http\Controllers\Tenants\ConfigurationController::class)->middleware('auth');
		
		// Backup controller is not a full resource
		Route::get('/backup', [App\Http\Controllers\BackupController::class, 'index'])->name('backup.index')->middleware('auth');
		Route::get('/backup/create', [App\Http\Controllers\BackupController::class, 'create'])->name('backup.create')->middleware('auth');
		Route::get('/backup/{backup}/restore', [App\Http\Controllers\BackupController::class, 'restore'])->name('backup.restore')->middleware('auth');
		Route::delete('/backup/{backup}', [App\Http\Controllers\BackupController::class, 'destroy'])->name('backup.destroy')->middleware('auth');
	});
	
	Route::get('/test', [App\Http\Controllers\Tenants\TenantTestController::class, 'index'])->name('test')->middleware('auth');
		
});
