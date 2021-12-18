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
	
	Auth::routes();
	
	Route::get('/', function () {return view('welcome');});
		
	Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');
	Route::get('/test', [App\Http\Controllers\Tenants\TenantTestController::class, 'index'])->name('test')->middleware('auth');
	Route::get('/info', [App\Http\Controllers\TestController::class, 'info'])->name('info')->middleware('auth');
	Route::get('/test/email', [App\Http\Controllers\TestController::class, 'email'])->name('test.email')->middleware('auth');
	
	Route::get('/change_password/change_password', [App\Http\Controllers\ChangePasswordController::class, 'change_password'])->name('change_password.change_password');
	Route::patch('/change_password/password', [App\Http\Controllers\ChangePasswordController::class, 'password'])->name('change_password.password');
	
	/*
	 * Warning: routes are parsed in order of declaration and resource matches all the sub urls
	 */
	// Special entry for the fullcalendar monthly view
	Route::get('/calendar/fullcalendar', [App\Http\Controllers\Tenants\CalendarEventController::class, 'fullcalendar'])
	->name('calendar.fullcalendar')->middleware('auth');
	
	Route::get('/calendar/json', [App\Http\Controllers\Tenants\CalendarEventController::class, 'json'])
	  ->name('calendar.json')->middleware('auth');
	
	// Classic ressource
	Route::resource('calendar', App\Http\Controllers\Tenants\CalendarEventController::class)->middleware('auth');
	
	// admin routes
	Route::group(['middleware' => ['admin']], function () {
		Route::resource('user', App\Http\Controllers\UserController::class)->middleware('auth');
		Route::resource('user_role', App\Http\Controllers\Tenants\UserRoleController::class)->middleware('auth');
		Route::resource('metadata', App\Http\Controllers\Tenants\MetadataController::class)->middleware('auth');
		
		Route::resource('configuration', App\Http\Controllers\Tenants\ConfigurationController::class)->middleware('auth');
		Route::resource('role', App\Http\Controllers\Tenants\RoleController::class)->middleware('auth');
		
		// Backup controller is not a full resource
		Route::get('/backup', [App\Http\Controllers\BackupController::class, 'index'])->name('backup.index')->middleware('auth');
		Route::get('/backup/create', [App\Http\Controllers\BackupController::class, 'create'])->name('backup.create')->middleware('auth');
		Route::get('/backup/{backup}/restore', [App\Http\Controllers\BackupController::class, 'restore'])->name('backup.restore')->middleware('auth');
		Route::delete('/backup/{backup}', [App\Http\Controllers\BackupController::class, 'destroy'])->name('backup.destroy')->middleware('auth');
		Route::get('/backup/{backup}', [App\Http\Controllers\BackupController::class, 'download'])->name('backup.download')->middleware('auth');
		Route::post('/backup', [App\Http\Controllers\BackupController::class, 'upload'])->name('backup.upload')->middleware('auth');
	});	
		
});

Route::middleware([
			'api',
			InitializeTenancyByDomain::class,
			PreventAccessFromCentralDomains::class,
	])->group(function () {
		Route::get('/api/calendar/fullcalendar', [App\Http\Controllers\Api\CalendarEventController::class, 'fullcalendar'])->name('calendar.fullcalendar');
		Route::resource('api/calendar', App\Http\Controllers\Api\CalendarEventController::class, ['as' => 'api']);
	});
	
		
